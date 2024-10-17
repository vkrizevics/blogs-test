<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $postsForLinks = Post::orderBy('created_at', 'desc')
            ->paginate(5);

        $posts = $postsForLinks
            ->load('comments', 'user', 'categories');

        $posts_count = count($posts);
        foreach ($posts as $i => $post) {
            $post->more_classes = '';

            $post->created_at_formatted = $post->getCreatedAtFormatted();
            $post->escaped_content = nl2br(htmlspecialchars($post->content), false);

            $post->is_author = Auth::check() && $post->user->id === Auth::id();

            if ($i === $posts_count - 1) {
                $posts->more_classes = 'pb-12';
            }
        }

        $csrf_token = csrf_token();
        $auth_user = Auth::check();
        $links = $postsForLinks->toArray();
        unset($links['data'], $links['links']);

        return Inertia::render('Posts/Index', compact('csrf_token', 'auth_user', 'posts', 'links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (!Auth::check()) {
            session(['redirect_after_auth' => $request->path()]);

            return redirect('login');
        }

        $csrf_token = csrf_token();
        $auth_user = Auth::check();

        return Inertia::render('Posts/Create', compact('csrf_token', 'auth_user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $post = new Post();

        $post->fill($request->validated());

        $post->user_id = $request->user()->id;

        $post->save();

        return redirect('posts/' . (int)$post->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['comments', 'user', 'categories']);

        $post->created_at_formatted = $post->getCreatedAtFormatted();
        $post->escaped_content = nl2br(htmlspecialchars($post->content), false);

        $post->is_author = Auth::check() && $post->user->id = Auth::id();

        $csrf_token = csrf_token();
        $auth_user = Auth::check();

        foreach ($post->comments as $comment) {
            $comment->is_author = Auth::check() && $comment->user->id = Auth::id();
        }

        return Inertia::render('Posts/Show', compact('csrf_token', 'auth_user', 'post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post, Request $request)
    {
        if (!Auth::check()) {
            session(['redirect_after_auth' => $request->path()]);

            return redirect('login');
        }

        $auth_user = Auth::check();

        $post->load('categories');

        $categories = $post->categories()
            ->orderBy('name')
            ->pluck('name');

        return Inertia::render('Posts/Edit', compact('post', 'auth_user', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        if (!Auth::check()) {

            return redirect('login');
        }

        $post->fill($request->validated());

        $post->save();

        $categoryNames = $request->input('categories', []);

        $categoryIds = [];
        foreach ($categoryNames as $categoryName) {
            $category = Category::where('name', $categoryName)
                ->get()
                ->first();

            if (!$category) {
                $category = new Category();
                $category->name = $categoryName;

                if (!$category->save()) {
                    continue;
                }
            }

            $categoryIds[] = $category->id;
        }

        $post->categories()->sync($categoryIds);

        return redirect('posts/' . (int)$post->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect('posts');
    }

    public function search(?string $postFragment)
    {
        $postsForLinks = Post::where(
                DB::raw('CONCAT(title, " ", content)'),
                'like',
                '%' . str_replace(' ', '%', addslashes($postFragment)) . '%'
            )
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $posts = $postsForLinks
            ->load('comments', 'user', 'categories');

        $posts_count = count($posts);
        foreach ($posts as $i => $post) {
            $post->more_classes = '';

            $post->created_at_formatted = $post->getCreatedAtFormatted();
            $post->escaped_content = nl2br(htmlspecialchars($post->content), false);

            $post->is_author = Auth::check() && $post->user->id === Auth::id();

            if ($i === $posts_count - 1) {
                $posts->more_classes = 'pb-12';
            }
        }

        $csrf_token = csrf_token();
        $auth_user = Auth::check();
        $links = $postsForLinks->toArray();
        unset($links['data'], $links['links']);

        return Inertia::render('Posts/Index', compact('csrf_token', 'auth_user', 'posts', 'links'));
    }
}

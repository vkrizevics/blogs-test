<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $postsForLinks = Post::orderBy('created_at', 'desc')
            ->paginate(10);

        $posts = $postsForLinks
            ->load('comments', 'user', 'categories');

        $posts_count = count($posts);
        foreach ($posts as $i => $post) {
            $post->more_classes = '';

            $post->created_at_formatted = $post->getCreatedAtFormatted();
            $post->escaped_content = nl2br(htmlspecialchars($post->content), false);

            $post->is_author = static::isAuthor($post);

            if ($i === $posts_count - 1) {
                $posts->more_classes = 'pb-12';
            }
        }

        $auth_user = Auth::check();
        $links = $postsForLinks->toArray();
        unset($links['data'], $links['links']);

        return Inertia::render('Posts/Index', compact('auth_user', 'posts', 'links'));
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

        $auth_user = Auth::check();

        return Inertia::render('Posts/Create', compact('auth_user'));
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

        $category_names = $request->input('categories', []);

        $category_ids = [];
        foreach ($category_names as $category_name) {
            $category = Category::where('name', $category_name)
                ->get()
                ->first();

            if (!$category) {
                $category = new Category();
                $category->name = $category_name;

                if (!$category->save()) {
                    continue;
                }
            }

            $category_ids[] = $category->id;
        }

        $post->categories()->sync($category_ids);

        return redirect('posts/' . (int)$post->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['comments', 'comments.user', 'user', 'categories']);

        $post->created_at_formatted = $post->getCreatedAtFormatted();
        $post->escaped_content = nl2br(htmlspecialchars($post->content), false);

        $post->is_author = static::isAuthor($post);

        $auth_user = Auth::check();

        foreach ($post->comments as $comment) {
            $comment->is_author = static::isAuthor($comment);
        }

        return Inertia::render('Posts/Show', compact('auth_user', 'post'));
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

        $category_names = $request->input('categories', []);

        $category_ids = [];
        foreach ($category_names as $category_name) {
            $category = Category::where('name', $category_name)
                ->get()
                ->first();

            if (!$category) {
                $category = new Category();
                $category->name = $category_name;

                if (!$category->save()) {
                    continue;
                }
            }

            $category_ids[] = $category->id;
        }

        $post->categories()->sync($category_ids);

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

    public function search(?string $post_fragment)
    {
        $posts_for_links = Post::where(
                DB::raw('CONCAT(title, " ", content)'),
                'like',
                '%' . str_replace(' ', '%', addslashes($post_fragment)) . '%'
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $posts = $posts_for_links
            ->load('comments', 'user', 'categories');

        $posts_count = count($posts);
        foreach ($posts as $i => $post) {
            $post->more_classes = '';

            $post->created_at_formatted = $post->getCreatedAtFormatted();
            $post->escaped_content = nl2br(htmlspecialchars($post->content), false);

            $post->is_author = static::isAuthor($post);

            if ($i === $posts_count - 1) {
                $posts->more_classes = 'pb-12';
            }
        }

        $auth_user = Auth::check();
        $links = $posts_for_links->toArray();
        unset($links['data'], $links['links']);

        return Inertia::render('Posts/Index', compact('auth_user', 'posts', 'links'));
    }

    public function user(?string $user_name)
    {
        $user = User::where('name', str_replace('_', ' ', $user_name))
            ->first();

        $postsForLinks = $user->posts()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $posts = $postsForLinks
            ->load('comments', 'user', 'categories');

        $posts_count = count($posts);
        foreach ($posts as $i => $post) {
            $post->more_classes = '';

            $post->created_at_formatted = $post->getCreatedAtFormatted();
            $post->escaped_content = nl2br(htmlspecialchars($post->content), false);

            $post->is_author = static::isAuthor($post);

            if ($i === $posts_count - 1) {
                $posts->more_classes = 'pb-12';
            }
        }

        $auth_user = Auth::check();
        $links = $postsForLinks->toArray();
        unset($links['data'], $links['links']);


        return Inertia::render('Posts/User', compact('auth_user', 'posts', 'links', 'user'));
    }

    protected function isAuthor(object $model): bool
    {
        return Auth::check() && $model->user->id === Auth::id();
    }
}

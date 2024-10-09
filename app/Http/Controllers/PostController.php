<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')
            ->paginate(5)
            ->load('comments', 'user');

        foreach ($posts as $i => $post) {
            $post->more_classes = '';

            $post->created_at_formatted = $post->getCreatedAtFormatted();
            $post->escaped_content = nl2br(htmlspecialchars($post->content), false);

            $post->show_link = route('posts.show', ['post' => $post->id]);

            $post->is_author = Auth::check() && $post->user->id = Auth::id();

            $post->edit = route('posts.edit', ['post' => $post->id]);

            $post->delete_form_id = 'delete-form-' . (int)$post->id;
            $post->delete_form_onclick = "event.preventDefault(); document.getElementById('delete-form-" . (int)$post->id . "').submit();";
            $post->destroy = route('posts.destroy', ['post' => $post->id]);

            $post->comment_link = route('posts.comments.create', ['post' => $post->id]);
        }

        $posts[$i]->more_classes = 'pb-12';

        $csrf_token = csrf_token();
        $auth_user = Auth::check();

        return Inertia::render('Posts/Index', compact('csrf_token', 'auth_user', 'posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        return view('posts.create');
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

        return view('posts.show', compact(
            'post'
        ));
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

        return view('posts.edit', compact('post'));
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
}

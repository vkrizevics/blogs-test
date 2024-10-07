<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post)
    {
        return redirect(route('posts.show', ['post' => $post->id]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Post $post)
    {
        return view('comments.create', compact('post'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Post $post)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $comment = new Comment();

        $comment->fill($request->validated());

        $comment->user_id = $request->user()->id;
        $comment->post_id = $post->id;

        $comment->save();

        return redirect('posts/' . (int)$post->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect('posts/' . (int)$comment->post_id);
    }
}

<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [PostController::class, 'index'])->name('index');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create')
    ->middleware(['auth']);

Route::post('/posts', [PostController::class, 'store'])->name('posts.store')
    ->middleware(['auth']);

Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit')
    ->middleware(['auth', 'can:update,post']);

Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update')
    ->middleware(['auth', 'can:update,post']);

Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy')
    ->middleware(['auth', 'can:delete,post']);


Route::get('/posts/search/{fragment}', [PostController::class, 'search'])->name('posts.search');

Route::get('/user/{name}', [PostController::class, 'user'])->name('posts.user');

Route::get('category/{category}', [CategoryController::class, 'show'])->name('category.show');


Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('posts.comments.index');

Route::get('/posts/{post}/comments/create', [CommentController::class, 'create'])->name('posts.comments.create')
    ->middleware(['auth']);

Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store')
    ->middleware(['auth']);

Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy')
    ->middleware(['auth', 'can:delete,comment']);

Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store')
    ->middleware(['auth']);
Route::get('/categories/search/{fragment}', [CategoryController::class, 'search'])->name('categories.search')
    ->middleware(['auth']);

require __DIR__.'/auth.php';

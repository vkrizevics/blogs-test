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
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('posts', PostController::class)->only('index', 'show');
Route::resource('posts', PostController::class)->only('create', 'store')
    ->middleware(['auth', 'verified']);

Route::resource('posts', PostController::class)->only('edit', 'update')
    ->middleware(['auth', 'verified', 'can:update,post']);

Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy')
    ->middleware(['auth', 'verified', 'can:delete,post']);

Route::get('posts/search/{fragment}', [PostController::class, 'search'])->name('posts.search');
Route::get('/user/{name}', [PostController::class, 'user'])->name('posts.user');

Route::get('category/{category}', [CategoryController::class, 'show'])->name('category.show');

Route::resource('posts.comments', CommentController::class)->middleware(['auth', 'verified'])->shallow()->except(['show']);

Route::resource('categories', CategoryController::class)->middleware(['auth', 'verified'])->except(['show']);
Route::get('categories/search/{fragment}', [CategoryController::class, 'search'])->middleware(['auth', 'verified'])->name('categories.search');

require __DIR__.'/auth.php';

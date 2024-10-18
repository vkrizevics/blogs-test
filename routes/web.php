<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('posts', PostController::class)->middleware(['auth', 'verified'])->except(['index']);
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

Route::resource('posts.comments', CommentController::class)->middleware(['auth', 'verified'])->shallow()->except(['show']);

Route::resource('categories', CategoryController::class)->middleware(['auth', 'verified']);

Route::get('categories/search/{fragment}', [CategoryController::class, 'search'])->middleware(['auth', 'verified'])->name('categories.search');

Route::get('posts/search/{fragment}', [PostController::class, 'search'])->middleware(['auth', 'verified'])->name('posts.search');

require __DIR__.'/auth.php';

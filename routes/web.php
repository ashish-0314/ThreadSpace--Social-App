<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'feed'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Communities
    Route::resource('communities', CommunityController::class)->except(['destroy']);
    Route::post('/communities/{community}/join', [CommunityController::class, 'join'])->name('communities.join');
    Route::post('/communities/{community}/leave', [CommunityController::class, 'leave'])->name('communities.leave');

    // Posts
    Route::resource('communities.posts', PostController::class)->shallow()->only(['create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::post('/posts/{post}/summarize', [PostController::class, 'summarize'])->name('posts.summarize');
    Route::get('/posts/{post}/repost',  [PostController::class, 'repostForm'])->name('posts.repost.form');
    Route::post('/posts/{post}/repost', [PostController::class, 'repostStore'])->name('posts.repost.store');

    // Comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/best-answer', [CommentController::class, 'markBestAnswer'])->name('comments.best_answer');

    // Voting
    Route::post('/vote', [VoteController::class, 'vote'])->name('vote');
});

// Public read routes
Route::get('/communities/{community}', [CommunityController::class, 'show'])->name('communities.show');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

require __DIR__.'/auth.php';

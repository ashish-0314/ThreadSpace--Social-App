<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'feed'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::middleware('auth')->group(function () {
    // Networking
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/users/{user}/follow', [NetworkController::class, 'toggleFollow'])->name('network.follow');
    Route::post('/users/{user}/connect', [NetworkController::class, 'sendRequest'])->name('network.connect');
    Route::post('/users/{user}/accept', [NetworkController::class, 'acceptRequest'])->name('network.accept');
    Route::delete('/users/{user}/connection', [NetworkController::class, 'removeConnection'])->name('network.disconnect');

    // Messaging
    Route::get('/messages/{userId?}', [MessageController::class, 'index'])->name('messages.index')->where('userId', '[0-9a-fA-F]{24}');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    // Settings route mapping to unified profile dashboard
    Route::get('/profile', function() {
        return redirect()->route('profile.show', ['user' => auth()->user()->id, 'tab' => 'settings']);
    })->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // /activity and /my-posts deprecated, unified in /u/{user}

    // Communities
    Route::resource('communities', CommunityController::class)->except(['destroy']);
    Route::post('/communities/{community}/join', [CommunityController::class, 'join'])->name('communities.join');
    Route::post('/communities/{community}/leave', [CommunityController::class, 'leave'])->name('communities.leave');

    // Posts – standalone must come BEFORE resource to prevent /posts/create
    //         being swallowed by the shallow resource's GET /posts/{post} route
    Route::get('/posts/create',          [PostController::class, 'createStandalone'])->name('posts.create.standalone');
    Route::post('/posts',                [PostController::class, 'storeStandalone'])->name('posts.store.standalone');
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
Route::get('/u/{user}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/api/users/{user}/card', [UserController::class, 'card'])->name('users.card');
Route::get('/communities/{community}', [CommunityController::class, 'show'])->name('communities.show');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Google Auth Routes
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

require __DIR__.'/auth.php';

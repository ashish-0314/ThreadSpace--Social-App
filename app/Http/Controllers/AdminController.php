<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Community;
use App\Mail\AccountDeleted;
use App\Mail\PostDeleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'posts' => Post::count(),
            'communities' => Community::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * List all users.
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * List all posts.
     */
    public function posts(Request $request)
    {
        $query = Post::with(['user', 'community']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.posts', compact('posts'));
    }

    /**
     * Delete a user account.
     */
    public function destroyUser(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot delete an administrator.');
        }

        $email = $user->email;
        $reason = $request->reason;

        // Clean up messages and connections to prevent orphaned data
        \App\Models\Message::where('sender_id', $user->id)->orWhere('receiver_id', $user->id)->delete();
        \App\Models\Connection::where('user_id', $user->id)->orWhere('connected_user_id', $user->id)->delete();

        // Delete the user
        $user->delete();

        // Send email
        try {
            Mail::to($email)->send(new AccountDeleted($reason));
        } catch (\Exception $e) {
            // Log the error if mail fails, but continue
            \Log::error("Failed to send AccountDeleted email to {$email}: " . $e->getMessage());
        }

        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Delete a post.
     */
    public function destroyPost(Request $request, Post $post)
    {
        $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        $email = $post->user ? $post->user->email : null;
        $postTitle = $post->title;
        $reason = $request->reason;

        // Delete the post
        $post->delete();

        // Send email if user exists
        if ($email) {
            try {
                Mail::to($email)->send(new PostDeleted($postTitle, $reason));
            } catch (\Exception $e) {
                // Log the error if mail fails, but continue
                \Log::error("Failed to send PostDeleted email to {$email}: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Post deleted successfully.');
    }
}

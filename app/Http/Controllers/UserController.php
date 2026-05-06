<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Return the HTML for the user popover card.
     */
    public function card(User $user)
    {
        // Get stats
        $followersCount = collect($user->followers())->count();
        $followingCount = collect($user->following())->count();

        // Check auth status
        $isFollowing = false;
        $connStatus = null;
        
        if (auth()->check()) {
            $isFollowing = auth()->user()->isFollowing($user->id);
            $connStatus = auth()->user()->connectionStatus($user->id);
        }

        return view('users.partials.popover-card', compact('user', 'followersCount', 'followingCount', 'isFollowing', 'connStatus'));
    }
}

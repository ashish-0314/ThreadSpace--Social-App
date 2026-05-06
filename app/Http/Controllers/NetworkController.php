<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follower;
use App\Models\Connection;
use App\Models\Notification;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    public function toggleFollow(User $user)
    {
        $me = auth()->user();
        if ($me->id === $user->id) return back();

        $existing = Follower::where('follower_id', $me->id)->where('user_id', $user->id)->first();

        if ($existing) {
            $existing->delete();
            $msg = "Unfollowed u/{$user->name}";
        } else {
            Follower::create(['follower_id' => $me->id, 'user_id' => $user->id]);
            
            // Notify the user
            Notification::create([
                'user_id' => $user->id,
                'from_user_id' => $me->id,
                'type' => 'follow'
            ]);
            
            $msg = "Now following u/{$user->name}";
        }

        return back()->with('success', $msg);
    }

    public function sendRequest(User $user)
    {
        $me = auth()->user();
        if ($me->id === $user->id) return back();

        $status = $me->connectionStatus($user->id);
        if ($status) return back()->with('info', 'Request already exists or connected.');

        Connection::create([
            'user_id' => $me->id,
            'connected_user_id' => $user->id,
            'status' => 'pending'
        ]);

        // Notify the recipient
        Notification::create([
            'user_id' => $user->id,
            'from_user_id' => $me->id,
            'type' => 'connection_request'
        ]);

        return back()->with('success', 'Connection request sent!');
    }

    public function acceptRequest(User $user)
    {
        $me = auth()->user();
        $conn = Connection::where('user_id', $user->id)
            ->where('connected_user_id', $me->id)
            ->where('status', 'pending')
            ->first();

        if ($conn) {
            $conn->update(['status' => 'accepted']);
            
            // Notify the sender
            Notification::create([
                'user_id' => $user->id,
                'from_user_id' => $me->id,
                'type' => 'connection_accepted'
            ]);

            // On LinkedIn, connecting usually triggers an automatic follow
            if (!$me->isFollowing($user->id)) {
                Follower::create(['follower_id' => $me->id, 'user_id' => $user->id]);
            }
            if (!$user->isFollowing($me->id)) {
                Follower::create(['follower_id' => $user->id, 'user_id' => $me->id]);
            }

            return back()->with('success', "You are now connected with {$user->name}!");
        }

        return back();
    }

    public function removeConnection(User $user)
    {
        $me = auth()->user();
        Connection::where(function($q) use ($me, $user) {
            $q->where('user_id', $me->id)->where('connected_user_id', $user->id);
        })->orWhere(function($q) use ($me, $user) {
            $q->where('user_id', $user->id)->where('connected_user_id', $me->id);
        })->delete();

        return back()->with('success', 'Connection removed.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a list of connections to chat with, and optionally load a specific chat.
     */
    public function index(Request $request, $userId = null)
    {
        $currentUser = Auth::user();

        // Get all accepted connections for the current user
        $connections = Connection::where(function($q) use ($currentUser) {
                $q->where('user_id', $currentUser->id)
                  ->orWhere('connected_user_id', $currentUser->id);
            })
            ->where('status', 'accepted')
            ->get();

        // Extract the actual User models from connections
        $chatUsers = collect();
        foreach ($connections as $conn) {
            if ($conn->user_id === $currentUser->id) {
                $chatUsers->push(User::find($conn->connected_user_id));
            } else {
                $chatUsers->push(User::find($conn->user_id));
            }
        }

        // Filter out nulls just in case users were deleted
        $chatUsers = $chatUsers->filter();

        // Get the active chat user if specified
        $activeUser = null;
        $messages = collect();

        if ($userId) {
            // Verify they are actually connected before allowing chat
            $isConnected = $chatUsers->contains('id', $userId);
            
            if (!$isConnected) {
                return redirect()->route('messages.index')->with('error', 'You can only message your connections.');
            }

            $activeUser = User::findOrFail($userId);

            // Fetch messages between currentUser and activeUser
            $messages = Message::where(function($q) use ($currentUser, $activeUser) {
                    $q->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $activeUser->id);
                })
                ->orWhere(function($q) use ($currentUser, $activeUser) {
                    $q->where('sender_id', $activeUser->id)
                      ->where('receiver_id', $currentUser->id);
                })
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark unread messages as read
            Message::where('sender_id', $activeUser->id)
                   ->where('receiver_id', $currentUser->id)
                   ->where('is_read', false)
                   ->update(['is_read' => true]);
        }

        return view('messages.index', compact('chatUsers', 'activeUser', 'messages'));
    }

    /**
     * Store a newly created message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|string',
            'body' => 'required|string|max:1000',
        ]);

        $currentUser = Auth::user();
        $receiverId = $request->input('receiver_id');

        // Verify connection exists
        $isConnected = Connection::where(function($q) use ($currentUser, $receiverId) {
            $q->where('user_id', $currentUser->id)->where('connected_user_id', $receiverId);
        })->orWhere(function($q) use ($currentUser, $receiverId) {
            $q->where('user_id', $receiverId)->where('connected_user_id', $currentUser->id);
        })->where('status', 'accepted')->exists();

        if (!$isConnected) {
            return response()->json(['error' => 'You must be connected to send messages.'], 403);
        }

        $message = Message::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $receiverId,
            'body' => $request->input('body'),
            'is_read' => false,
        ]);

        return redirect()->route('messages.index', $receiverId);
    }
}

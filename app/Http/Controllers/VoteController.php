<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function vote(Request $request)
    {
        $validated = $request->validate([
            'votable_id'   => 'required|string',
            'votable_type' => 'required|in:Post,Comment',
            'value'        => 'required|in:1,-1',
        ]);

        $userId     = auth()->id();
        $modelClass = "App\\Models\\" . $validated['votable_type'];
        $votable    = $modelClass::find($validated['votable_id']);

        if (!$votable) {
            return back()->with('error', 'Item not found.');
        }

        // Ensure integer fields exist (guard against null stored in MongoDB)
        $upvotes   = (int)($votable->upvotes ?? 0);
        $downvotes = (int)($votable->downvotes ?? 0);
        $value     = (int)$validated['value']; // 1 or -1

        $existingVote = Vote::where('user_id', $userId)
            ->where('votable_id', $validated['votable_id'])
            ->where('votable_type', $modelClass)
            ->first();

        if ($existingVote) {
            $oldValue = (int)$existingVote->value;

            if ($oldValue === $value) {
                // Toggle off — undo the existing vote
                $existingVote->delete();
                if ($value === 1) {
                    $upvotes = max(0, $upvotes - 1);
                } else {
                    $downvotes = max(0, $downvotes - 1);
                }
            } else {
                // Switching vote direction
                $existingVote->value = $value;
                $existingVote->save();

                if ($value === 1) {
                    $upvotes   = $upvotes + 1;
                    $downvotes = max(0, $downvotes - 1);
                } else {
                    $downvotes = $downvotes + 1;
                    $upvotes   = max(0, $upvotes - 1);
                }
            }
        } else {
            // Brand new vote
            Vote::create([
                'user_id'      => $userId,
                'votable_id'   => $validated['votable_id'],
                'votable_type' => $modelClass,
                'value'        => $value,
            ]);

            if ($value === 1) {
                $upvotes = $upvotes + 1;
            } else {
                $downvotes = $downvotes + 1;
            }
        }

        // Persist the updated counters explicitly (avoid MongoDB increment issues)
        $votable->upvotes   = $upvotes;
        $votable->downvotes = $downvotes;
        $votable->save();

        // Update quality score for posts
        $post = null;
        if ($validated['votable_type'] === 'Post') {
            $post = $votable;
        } elseif ($validated['votable_type'] === 'Comment') {
            $post = $votable->post;
        }

        if ($post) {
            $post->refreshQualityScore();
        }

        // Reputation: add +1 to post/comment owner per upvote
        if ($value === 1) {
            $owner = $votable->user;
            if ($owner) {
                $reputation = $owner->reputation ?? [];
                $category = ($validated['votable_type'] === 'Post')
                    ? ($votable->intent ?? 'General')
                    : ($votable->post->intent ?? 'General');
                $reputation[$category] = ((int)($reputation[$category] ?? 0)) + 1;
                $owner->reputation = $reputation;
                $owner->save();

                // Notify the owner of the upvote (once per user per item, no spam)
                if ($owner->id !== $userId) {
                    $postId = ($validated['votable_type'] === 'Post') ? (string)$votable->id : (string)($votable->post_id ?? '');
                    $alreadyNotified = \App\Models\Notification::where('user_id', $owner->id)
                        ->where('from_user_id', $userId)
                        ->where('type', 'upvote')
                        ->where('post_id', $postId)
                        ->exists();
                    if (!$alreadyNotified) {
                        \App\Models\Notification::create([
                            'user_id'      => $owner->id,
                            'from_user_id' => $userId,
                            'type'         => 'upvote',
                            'post_id'      => $postId,
                        ]);
                    }
                }
            }
        }

        $score = $upvotes - $downvotes;

        // Determine what the user's current vote is after this action
        $userVote = null;
        if ($existingVote && !$existingVote->exists) {
            $userVote = null; // was toggled off
        } elseif ($existingVote) {
            $userVote = $existingVote->value;
        } else {
            // new vote - find it
            $latestVote = Vote::where('user_id', $userId)
                ->where('votable_id', $validated['votable_id'])
                ->where('votable_type', $modelClass)
                ->first();
            $userVote = $latestVote ? (int)$latestVote->value : null;
        }

        // Return JSON for AJAX requests, redirect for regular form posts
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'score'    => $score,
                'upvotes'  => $upvotes,
                'downvotes'=> $downvotes,
                'userVote' => $userVote,
            ]);
        }

        return back();
    }
}

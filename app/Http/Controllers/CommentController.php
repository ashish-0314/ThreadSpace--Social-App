<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,_id'
        ]);

        $depth = 0;
        if (!empty($validated['parent_id'])) {
            $parent = Comment::find($validated['parent_id']);
            $depth = $parent ? $parent->depth + 1 : 0;
        }

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
            'depth' => $depth,
            'upvotes' => 0,
            'downvotes' => 0,
            'is_best_answer' => false,
        ]);

        return back()->with('success', 'Comment added!');
    }

    public function markBestAnswer(Comment $comment)
    {
        $post = $comment->post;
        
        // Only post owner can mark best answer, and only if intent is Question
        if (auth()->id() !== $post->user_id || $post->intent !== 'Question') {
            abort(403);
        }

        // Unmark previous best answer
        Comment::where('post_id', $post->id)->where('is_best_answer', true)->update(['is_best_answer' => false]);

        $comment->update(['is_best_answer' => true]);

        return back()->with('success', 'Marked as best answer!');
    }
}

<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'post_id', 'user_id', 'parent_id', 'content', 'upvotes', 'downvotes', 'is_best_answer', 'depth'
    ];

    protected $casts = [
        'upvotes'       => 'integer',
        'downvotes'     => 'integer',
        'depth'         => 'integer',
        'is_best_answer'=> 'boolean',
    ];

    protected $attributes = [
        'upvotes'        => 0,
        'downvotes'      => 0,
        'depth'          => 0,
        'is_best_answer' => false,
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }
}

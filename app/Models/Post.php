<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'community_id', 'user_id', 'title', 'type', 'content', 'image_url',
        'intent', 'upvotes', 'downvotes', 'quality_score', 'engagement_time',
        'is_repost', 'original_post_id', 'repost_comment',
    ];

    protected $casts = [
        'upvotes'       => 'integer',
        'downvotes'     => 'integer',
        'quality_score' => 'integer',
        'is_repost'     => 'boolean',
    ];

    protected $attributes = [
        'upvotes'       => 0,
        'downvotes'     => 0,
        'quality_score' => 0,
        'is_repost'     => false,
    ];

    public function originalPost()
    {
        return $this->belongsTo(Post::class, 'original_post_id');
    }

    public function reposts()
    {
        return $this->hasMany(Post::class, 'original_post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }
}

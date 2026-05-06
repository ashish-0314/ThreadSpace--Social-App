<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Notification extends Model
{
    // Types: follow, connection_request, connection_accepted, comment, reply, upvote, repost
    protected $fillable = ['user_id', 'from_user_id', 'type', 'post_id', 'is_read'];

    protected $casts = [
        'is_read' => 'boolean'
    ];

    protected $attributes = [
        'is_read' => false
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}

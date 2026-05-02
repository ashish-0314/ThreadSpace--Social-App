<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Community extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'rules', 'creator_id', 'moderators', 'members_count'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

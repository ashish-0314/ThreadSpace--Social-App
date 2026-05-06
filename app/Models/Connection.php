<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Connection extends Model
{
    // Statuses: pending, accepted
    protected $fillable = ['user_id', 'connected_user_id', 'status'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'connected_user_id');
    }
}

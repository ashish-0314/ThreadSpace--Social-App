<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'bio',
        'reputation',
        'avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function followers()
    {
        return $this->hasMany(Follower::class, 'user_id');
    }

    public function following()
    {
        return $this->hasMany(Follower::class, 'follower_id');
    }

    public function isFollowing($userId)
    {
        return Follower::where('follower_id', $this->id)->where('user_id', $userId)->exists();
    }

    public function connectionStatus($userId)
    {
        $conn = Connection::where(function($q) use ($userId) {
            $q->where('user_id', $this->id)->where('connected_user_id', $userId);
        })->orWhere(function($q) use ($userId) {
            $q->where('user_id', $userId)->where('connected_user_id', $this->id);
        })->first();

        return $conn ? $conn->status : null;
    }

    public function isConnectedWith($userId)
    {
        return $this->connectionStatus($userId) === 'accepted';
    }

    /**
     * Check if the user is the administrator.
     */
    public function isAdmin()
    {
        return $this->email === env('ADMIN_EMAIL');
    }
}

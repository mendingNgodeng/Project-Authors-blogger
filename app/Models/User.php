<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Story;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $connection = 'mongodb';
    protected $collection = 'users';
    protected $fillable = [
        '_id',
        'name',
        'username',
        'email',
        'role',
        'pic',
        'password',
        'last_seen'
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


    // check online users
    public function isOnline()
    {
        // return $this->last_seen && Carbon::parse($this->last_seen)->diffInSeconds(now()) <= 10;

        if (!$this->last_seen) return false;

    $minutes = Carbon::parse($this->last_seen)->diffInMinutes(now());

    logger("User {$this->name} was last seen {$minutes} minutes ago");

    return $minutes <= 5;
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'poster', 'username');
    }

    public function story()
    {
        return this->belongsTo(Story::class,'id_user','_id');
    }

}

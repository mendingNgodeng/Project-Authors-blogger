<?php

namespace App\Models;


use MongoDB\Laravel\Eloquent\Model;
use App\Models\Media;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Notifications\Notifiable;

class Post extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'post';
    protected $fillable = [
        'title',
        'content',
        'poster', //ID AUTH FROM USER LOGIN SESSIOM
        'category',
        'tags',
        'role', // ROLE AUTH FROM USER LOGIN SESSION
        // 'picture',
        // 'desc',
        // 'video',
        'status',
    ];

    public function media()
    {
    return $this->hasMany(Media::class, 'post_id', '_id');
    }

    public function user()
    {
    return $this->belongsTo(User::class, 'poster', 'username');
    }
    
    public function comment()
    {
        return $this->hasMany(Comment::class,'post_id','_id');
    }
}
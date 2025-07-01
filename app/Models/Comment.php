<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Post;
use App\Models\User;
use Illuminate\Notifications\Notifiable;

class Comment extends Model
{
      
    protected $connection = 'mongodb';
    protected $collection = 'comments';

    protected $fillable = [
        'comment',     // commend
        'post_id',     // linked post
        'user_id',      // user or Guest if not login
        // 'media'        // 'image' or 'video' if i can do it lol
    ];

    public function post()
    {
        return $this->belongsTo(Post::class,'post_id','_id');
    }

    public function user()
    {
    return $this->belongsTo(User::class, 'user_id', '_id');
    }
}

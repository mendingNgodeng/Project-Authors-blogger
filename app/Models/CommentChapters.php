<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\User;
use Illuminate\Notifications\Notifiable;

class CommentChapters extends Model
{
      
    protected $connection = 'mongodb';
    protected $collection = 'comments';

    protected $fillable = [
        'comment',     // commend
        'chapter_id',     // linked chapt
        'user_id',      // user or Guest if not login
        // 'media'        // 'image' or 'video' if i can do it lol
    ];

  public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
}

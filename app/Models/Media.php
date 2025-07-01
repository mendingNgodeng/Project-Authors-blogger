<?php

namespace App\Models;



use MongoDB\Laravel\Eloquent\Model;
use App\Models\Post;
use Illuminate\Notifications\Notifiable;

class Media extends Model
{
    
    protected $connection = 'mongodb';
    protected $collection = 'media';

    protected $fillable = [
        'id_poster',     // uploader
        'post_id',     // linked post
        'url',
        'type',        // 'image' or 'video'
    ];
    public $timestamps = true; 
    
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', '_id');
    }

     
}
<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Story;
use App\Models\Likes;
use MongoDB\BSON\ObjectId;
class Chapters extends Model
{
        protected $connection = 'mongodb';
    protected $collection = 'chapters';

    protected $fillable = [
        '_id',
        'id_story', 
        'title',
        'content',
        'status'
    ];
    public $timestamps = true;


    public function story()
    {
    return $this->belongsTo(Story::class, 'id_story', '_id');
    }

    public function likes()
{
    return $this->hasMany(Likes::class, 'liked_data', '_id');
}

}

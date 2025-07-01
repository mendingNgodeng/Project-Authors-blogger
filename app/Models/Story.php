<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Chapters;
class Story extends Model
{
     protected $connection = 'mongodb';
    protected $collection = 'story';

    protected $fillable = [
        '_id',
        'id_user', // from User data as the author
        'cover',
        'title',
        'summary',
        'author',     // Maker
        'chapters',
        'status',     // Published or Draft
        'type',        // Fanfic or Original
        'genre',
        'completed',
    ];
    // i hate you :(
    protected $casts = [
    '_id' => 'string',
];
    public $timestamps = true;

    
    public function chapters()
    {
    return $this->hasMany(Chapters::class, 'id_story', '_id');
    }
}

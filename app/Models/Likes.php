<?php

namespace App\Models;


use MongoDB\Laravel\Eloquent\Model;
use App\Models\User;
use Illuminate\Notifications\Notifiable;

class Likes extends Model
{
     protected $fillable = ['user_id', 'liked_data'];

      public function user()
    {
        return $this->belongsTo(User::class,'user_id','_id');
    }
}

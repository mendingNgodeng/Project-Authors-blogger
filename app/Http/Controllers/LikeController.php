<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Likes;
class LikeController extends Controller
{
    public function like($id)
    {
    $user = Auth::user();

    $like = Likes::where('user_id', $user->id)
                 ->where('liked_data', $id)
                 ->first();

    if ($like) {
        $like->delete();
        return response()->json(['liked' => false]);
    } else {
        Likes::create([
            'user_id' => $user->id,
            'liked_data' => $id
        ]);
        return response()->json(['liked' => true]);
    }
    }
}

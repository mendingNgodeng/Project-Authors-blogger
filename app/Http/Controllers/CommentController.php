<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth; //Auth
class CommentController extends Controller
{

    public function index()
{
    $comments = Comment::with(['user', 'post'])->latest()->get();
    return view('blog.comments.index',compact("comments")); // will try make my life harder with ajax . Update: gak jadi
}

public function deleteComment($id)
{
    $comment = Comment::findOrFail($id);
    $comment->delete();

    return response()->json(['success' => true]);
}
}

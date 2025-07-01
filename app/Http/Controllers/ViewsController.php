<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Media;
use App\Models\Comment;
use App\Events\CommentSent;
use App\Http\Requests\CommentRequest as req; // data validate input data

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; //Auth
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash; //password hash
use Illuminate\Support\Facades\Session; //sesssion
use Illuminate\Support\Facades\Storage; //storage
use App\Helpers\HtmlSanitizer; //sanitizer
use Illuminate\View\View;

class ViewsController extends Controller
{
    

    public function index()
    {
        $user = Auth::user();
        // $post = Post::with(['media','user','comment.user'])->orderBy('created_at','desc')->get()->withRelationshipAutoLoading();
        $post = Post::with(['media','user','comment.user'])->orderBy('created_at','desc')->get();
        // $comment = Post::with('comment.user')->findOrFail($user->_id);
        return view('blog.user_view.posts',compact('post'));
    }

    public function comment(req $req, $id, $id_post)
    {
     
        $data = $req->validated();

        $post = post::findOrFail($id_post);

        $comment = Comment::create([
            'comment' => HtmlSanitizer::clean($data['comment']),
            'post_id' => $post->_id,
            'user_id' => $id,
        ]);

         $comment->load('user');
         
if (!$comment->user) {
    \Log::error('User not found for comment ID: ' . $comment->_id . ' with user_id: ' . $comment->user_id);
}
        $user = $comment->user;
        broadcast(new CommentSent($comment))->toOthers();

    // return response()->json(['comment' => $comment]);
//     return response()->json([

//     'status' => 'success',
//     'comment' => $comment,
//     'pic' => asset($user?->pic ?? 'storage/users/guest_.png'),
//     'user_name' => $user?->name ?? 'Guest',
//     'created_at' => $comment->created_at->diffForHumans(),
// ]);

        return response()->json([
        'user_name' => $comment->user->name ?? 'Guest',
        'pic' => $comment->user->pic ?? '/storage/users/guest_.png',
        'comment' => [
            'comment' => $comment->comment,
            'created_at' => now()->format('d M Y, H:i')
        ]
    ]);

        // return to_route('posts.beranda');  
    }

    // uhhh what the sigma
    public function store(req $req)
{
    $request->validated();

    $comment = Comment::create([
        'post_id' => $request->post_id,
        'user_id' => auth()->id(),
        'comment' => $request->comment,
    ]);

    // Load user relationship for display
    $comment->load('user');

    return response()->json([
        'status' => 'success',
        'comment' => $comment,
        'user_name' => $comment->user->name,
        'created_at' => $comment->created_at->diffForHumans(),
    ]);
}
}
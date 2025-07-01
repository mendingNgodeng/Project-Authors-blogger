<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Chapters;
use App\Models\Likes;
use App\Models\Comment;
use App\Models\CommentChapters;
use App\Models\Story;
use App\Models\Media;
use App\Models\Post;

use App\Http\Requests\UsersRequestAdd as dataReq; // data validate input data

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; //Auth
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash; //password hash
use Illuminate\Support\Facades\Session; //sesssion
use Illuminate\Support\Facades\Storage; //storage
use Illuminate\Support\Facades\Validator; //validator i guess
use Illuminate\View\View;
use App\Helpers\HtmlSanitizer; //sanitizer


class UsersController extends Controller
{
    public function __construct()
    {
        if(Auth::user()->role == 'user')
        {
            return to_route('/');
        }
    }
    public function index()
    {
        $role = Auth::user()->role;
        $id_user = Auth::user()->_id;
        $poster = Auth::user()->name;

        
        $user = User::all();
        return view('blog.users.index',compact('user','poster','id_user','role'));
    }

    public function api()
{
    return User::all();
}

public function show($id)
{
    return response()->json(User::findOrFail($id));
}

    public function create()
    {
        return view('blog.users.create');
    }

    public function store(dataReq $req)
    {
        $data = $req->validated();
          $password = empty($data['password']) ? $data['username'] : $data['password'];

        if($req->hasFile('pic'))
        {
            $file = $req->file('pic');
            $filename = time().'_'.$file->getClientOriginalName();
            $path=$file->storeAs('users',$filename,'public');
            
            // save ke DB
            $data['pic'] = '/storage/'.$path;
        }

          User::create([
            'name' => HtmlSanitizer::clean($data['name']),
            'username' => HtmlSanitizer::clean($data['username']),
            'email' => HtmlSanitizer::clean($data['email']),
            'password' => Hash::make($data['password']),
            'pic' => $data['pic'],
            'role' => 'user'
        ]);

        // return to_route('users.index');
         return response()->json(['message' => 'User Created']);
    }

    public function detail($id)
    {
        $data = User::findOrFail($id);
        return view('blog.users.detail', compact('data'));
    }

    public function edit($id)
    {
        $data = User::findOrFail($id);
        return view('blog.users.edit', compact('data'));
    }

    public function update(dataReq $req,$id)
    {
        $user = User::findOrFail($id);

        $data = $req->validated();
        
        $user->name = HtmlSanitizer::clean($data['name']);
        $user->username = HtmlSanitizer::clean($data['username']);
        $user->email = HtmlSanitizer::clean($data['email']);
        $user->role = HtmlSanitizer::clean($data['role']);
        
        // upload file
         if($req->hasFile('pic'))
        {

        if ($user->pic && Storage::disk('public')->exists(str_replace('/storage/', '', $user->pic))) {
        Storage::disk('public')->delete(str_replace('/storage/', '', $user->pic));
        }

            $file = $req->file('pic');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('users',$filename,'public');

            $data['pic'] = '/storage/'.$path;
        }else{
            // take the previous insert file
            $data['pic'] = $user->pic;
        }
         // set updated pic
        $user->pic = $data['pic'];

        // only update if password input is filled
        if(!empty($data['password']))
        {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        // return to_route('users.index');
        //  return response()->json(['message' => 'User updated']);
          return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!',
            'data'    => $user,
            'id_user' => (string) $user->_id
        ]);
    }

    // 
    

    public function delete(Request $req, $id)
{
    $user = User::findOrFail($id);

    //  Delete Stories and Chapters 
    $stories = Story::where('id_user', $id)->get();

    foreach ($stories as $story) {
        // Delete cover if exists
        if ($story->cover && Storage::disk('public')->exists(str_replace('/storage/', '', $story->cover))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $story->cover));
        }

        // Delete all chapters related to this story
        Chapters::where('id_story', (string) $story->_id)->delete();

        // Delete the story itself
        $story->delete();
    }

    //  Delete Posts and their Media 
    $posts = Post::where('poster', $user->username)->get();

    foreach ($posts as $post) {
        // Delete media linked to this post
        $mediaItems = Media::where('id_post', (string) $post->_id)->get();

        foreach ($mediaItems as $media) {
            $filepath = str_replace('/storage', '', $media->url);
            if (Storage::disk('public')->exists($filepath)) {
                Storage::disk('public')->delete($filepath);
            }
            $media->delete();
        }

        // Delete post itself
        $post->delete();
    }

    //  Delete Media directly tied to user (not post) 
    $userMedia = Media::where('id_poster', $id)->get();
    foreach ($userMedia as $media) {
        $filepath = str_replace('/storage', '', $media->url);
        if (Storage::disk('public')->exists($filepath)) {
            Storage::disk('public')->delete($filepath);
        }
        $media->delete();
    }

    // Delete Profile Picture
    if ($user->pic && file_exists(public_path($user->pic))) {
        unlink(public_path($user->pic));
    }

    // delete likesssss
    Likes::where('user_id',$id)->delete();
    

    // Delete Comments
    Comment::where('user_id', $id)->delete();
    CommentChapters::where('user_id', $id)->delete();

    // Finally delete user
    $user->delete();

    return response()->json(['message' => 'User and data sociated with are deleted']);
}


    // ajax
      public function storeAjax(dataReq $req)
    {
        $data = $req->validated();
       
        $data['pic'] = null;
        if($req->hasFile('pic'))
        {
            $file = $req->file('pic');
            $filename = time().'_'.$file->getClientOriginalName();
            $path=$file->storeAs('users',$filename,'public');
            
            // save ke DB
            $data['pic'] = '/storage/'.$path;
        }

          $data_users = User::create([
            'name' => HtmlSanitizer::clean($data['name']),
            'username' => HtmlSanitizer::clean($data['username']),
            'email' => HtmlSanitizer::clean($data['email']),
            'password' => Hash::make($data['password']),
            'pic' => $data['pic'],
            'role' => $data['role'] ?? 'user',
            'status' => ''
        ]);

       
        // return to_route('users.index');
         return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!',
            'data'    => $data_users,
            'id_user' => (string) $data_users->_id
        ]);
    }

}

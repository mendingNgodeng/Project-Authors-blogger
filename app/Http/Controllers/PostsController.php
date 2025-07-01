<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Media;
use App\Models\Comment;

use App\Http\Requests\PostsRequest as dataReq; // data validate input data

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; //Auth
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash; //password hash
use Illuminate\Support\Facades\Session; //sesssion
use Illuminate\Support\Facades\Storage; //storage
use Illuminate\View\View;
use Illuminate\Support\Str; //support STR for randomization
use App\Helpers\HtmlSanitizer; //sanitizer


class PostsController extends Controller
{
    public function index()
    {
       $user = Auth::user();

    if ($user->role === 'admin') {
        // Admin sees all posts
        $post = Post::with('media')
                     ->orderBy('created_at', 'desc')
                     ->get();
    } else {
        // Non-admin sees only their own posts
        $post = Post::with('media')
                     ->where('poster', Auth::user()->username) // or $user->_id if that's what you store
                     ->orderBy('created_at', 'desc')
                     ->get();
    }
        return view('blog.posts.index', compact('post'));

    }

    // public function User_index()
    // {
    //     $post = Post::with('media')->orderBy('created_at','desc')->get();
    //     return view('blog.posts.index', compact('post'));
    // }

    public function detail($id)
    {
        $post = Post::with('media')->findOrFail($id);
        return view('blog.posts.detail', compact('post'));
    }


    public function create()
    {
        return view('blog.posts.create');
    }
    public function store(dataReq $req)
    {
        $data = $req->validated();

        $post = Post::create([
            'title' => HtmlSanitizer::clean($data['title']),
            'content' => HtmlSanitizer::clean($data['content']),
            'poster' => Auth::user()->username,
            'category' => isset($data['category']) ? HtmlSanitizer::clean($data['category']) : null,
            'tags' => isset($data['tags']) ? HtmlSanitizer::clean($data['category']) : null,
            'role' => Auth::user()->role,
            'status' => HtmlSanitizer::clean($data['status']),
        ]);

          if($req->hasFile('media'))
        {
            info('Files received:', [count($req->file('media'))]);

            foreach($req->file('media') as $file)
            {
              $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // only name, no extension
            $extension = $file->getClientOriginalExtension();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $name); // replace spaces and special chars with underscore
            $filename =time().'_'.Str::random(8).'_'.$cleanName.".".$extension;
               try {
            $path = $file->storeAs('posts',$filename,'public');

    Media::create([
        'post_id' => (string) $post->_id,
        'id_poster' => Auth::user()->_id,
        'url' => Storage::url($path),
        'type' => Str::startsWith($file->getMimeType(), 'video') ? 'video' : 'image',
    ]);
} catch (\Exception $e) {
    \Log::error('Media save failed: ' . $e->getMessage());
}
         
            }
            
        }

        // return to_route('posts.index');
         return response()->json([
        'message' => 'Post created successfully',
        'data' => $post,
        'id_post' => (string) $post->_id,
        'data_media' => $post->load('media'),
    ]);
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('blog.posts.edit', compact('post'));
    }

    public function update(dataReq $req, $id)
    {
        $post = Post::findOrFail($id);

        
        $data = $req->validated();

        // update post
        $post->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'category' => $data['category'],
            'tags' => $data['tags'],
            'status' => $data['status'],
        ]);

        // Media processing: delete old one if input/replacing media
        // if not input or replace, keep the old one. yes it wok, the fel not cans wen not uplot
        if($req->hasFile('media'))
        {
            $oldMedia = Media::where('post_id',$post->_id)->get();

            foreach($oldMedia as $m)
            {
                // delete
                $filepath = str_replace('/storage','',$m->url);
                Storage::disk('public')->delete($filepath);
                $m->delete();
            }

            // saves new one
            foreach($req->file('media') as $file)
            {
                 $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // only name, no extension
            $extension = $file->getClientOriginalExtension();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $name); // replace spaces and special chars with underscore
            $filename =time().'_'.Str::random(8).'_'.$cleanName.".".$extension;

               try {
            $path = $file->storeAs('posts',$filename,'public');

            // decided media video/image/or audio
               $type = $file->getMimeType();
        $mediaType = Str::startsWith($type, 'video') ? 'video' :
                     (Str::startsWith($type, 'audio') ? 'audio' : 'image');


                // upload new media
     Media::create([
        'post_id' => (string) $post->_id,
        'id_poster' => Auth::user()->_id,
        'url' => Storage::url($path),
        // 'type' => Str::startsWith($file->getMimeType(), 'video') ? 'video' : 'image',
        'type' => $mediaType,
       ]);

    } catch (\Exception $e) {
    \Log::error('Media save failed: ' . $e->getMessage());
    }   
            }
        }

        // return to_route('posts.index');
          return response()->json([
        'message' => 'Post created successfully',
        'data' => $post,
        'id_post' => (string) $post->_id,
        'data_media' => $post->load('media'),
    ]);
    }


    public function delete($id)
{
    $post = Post::findOrFail($id);

    // Get all related media
    $mediaItems = Media::where('post_id', (string) $post->_id)->get();
    $commentItems = Comment::where('post_id', (string) $post->_id)->get();

    foreach ($mediaItems as $media) {
        // Delete file from storage
        $filepath = str_replace('/storage', '', $media->url);
        Storage::disk('public')->delete($filepath);

        // Delete media record
        $media->delete();
    }

    // Finally delete the post and the comment attached to it
    $post->comment()->delete();
    $post->delete();

    // return to_route('posts.index')->with('success', 'Post and related media deleted successfully!');
     return response()->json([
        'message' => 'Post Deleted',
    ]);
}
}



<?php
// to manage all of story page and chapters of each stories
namespace App\Http\Controllers;

use App\Helpers\HtmlSanitizer; //sanitizer
use Illuminate\Support\Facades\Storage; //storage
use Illuminate\Support\Facades\Auth; //Auth
use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\User;
use App\Models\Likes;
use App\Models\Chapters;
use App\Http\Requests\StoryReq as req; // data validate input data
class StoryController extends Controller
{
    public function index(Request $request)
    {

    $query = $request->input('query');
    $category = $request->input('category', 'title');
   
    // Sanitize category BEFORE using it
    if (!in_array($category, ['title', 'author', 'type', 'genre'])) {
        $category = 'title'; //default eeeee
    }

    $story = Story::with(['chapters' => function ($q) {
        $q->where('status', 'Published');
    }]) //only published chaps
    ->where('status', 'Published') // only published story
    ->when($query, function ($q) use ($category, $query) {
        $q->where($category, 'like', '%' . $query . '%');
    })
    ->get();

    // BRUHHH now worked
     foreach ($story as $s) {
        foreach ($s->chapters as $c) {
            $c->likes_count = Likes::where('liked_data', (string) $c->_id)->count();
        }
    }
    // AJAX support
    if ($request->ajax()) {
        return view('blog.story.storyList', compact('story'))->render();
    }

    return view('blog.story.index', compact('story'));
  
       
    }
    
    public function AddStory($id = null)
    {   
         if ($id) {
            $story = Story::findOrFail($id);
            $chapter = Chapters::with('story')->where('id_story',$id)->get();
            //  $post = Post::with('media')
            //          ->orderBy('created_at', 'desc')
            //          ->get();
        return view('blog.story.create_story', compact('story','chapter'));
    }
        return view('blog.story.create_story');
    }

    public function storeStory(req $req)
    {
        $data = $req->validated();

          if($req->hasFile('cover'))
        {
            $file = $req->file('cover');
            $filename = time().'_'.$file->getClientOriginalName();
            $path=$file->storeAs('cover',$filename,'public');
            
            // save ke DB
            $data['cover'] = '/storage/'.$path;
        }

          $story = Story::create([
            'id_user' => Auth::user()->_id,
            'author' => Auth::user()->username,
            'title' => HtmlSanitizer::clean($data['title']),
            'summary' => HtmlSanitizer::clean($data['summary']),
            'type' => HtmlSanitizer::clean($data['type']),
            'completed' => HtmlSanitizer::clean($data['completed']),
            'genre' => HtmlSanitizer::clean($data['genre']),
            'cover' => isset($data['cover']) ? HtmlSanitizer::clean($data['cover']) : null,
            'status' => HtmlSanitizer::clean($data['status']),
        ]);

        // return to_route('users.index');
         return response()->json([
            'message' => 'Story successfully created!',
            'story' => $story,
            'id_story' => (string) $story->_id,
        ]);
    }

    public function updateStory(req $req,$id)
    {
        $story = Story::findOrFail($id);
        $data = $req->validated();

        $story->title = HtmlSanitizer::clean($data['title']);
        $story->summary = HtmlSanitizer::clean($data['summary']);
        $story->type = HtmlSanitizer::clean($data['type']);
        $story->completed = HtmlSanitizer::clean($data['completed']);
        $story->genre = HtmlSanitizer::clean($data['genre']);
        $story->status = HtmlSanitizer::clean($data['status']);
       

        if($req->hasFile('cover'))
        {
        if ($story->cover && Storage::disk('public')->exists(str_replace('/storage/', '', $story->cover))) {
        Storage::disk('public')->delete(str_replace('/storage/', '', $story->cover));
        }

            $file = $req->file('cover');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('cover',$filename,'public');

            $data['cover'] = '/storage/'.$path;
        }else{
            // take the previous insert file
            $data['cover'] = $story->cover;
        }
         // set updated cover
        $story->cover = HtmlSanitizer::clean($data['cover']);

        $story->save();

        return response()->json([
            'message' => 'Story successfully Updated!',
            'story' => $story,
            'story_id' => (string) $story->_id,
        ]);

    }

    public function deleteStory($id)
{
    $story = Story::findOrFail($id);
    $Chapter = Chapters::where('id_story',$id);
    if ($story->cover && Storage::disk('public')->exists(str_replace('/storage/', '', $story->cover))) {
        Storage::disk('public')->delete(str_replace('/storage/', '', $story->cover));
    }

    $story->delete();
    $Chapter->delete();
    return response()->json(['message' => 'Story successfully deleted.']);
}

  public function manage()
    {
         $user = Auth::user();

    if ($user->role === 'admin') {
        // Admin sees all posts
        $story =Story::all();
    } else {
        // Non-admin sees only their own posts
        $story = Story::where('id_user', Auth::user()->_id) // $user->_id 
                     ->orderBy('created_at', 'desc')
                     ->get();
    }
    foreach ($story as $s) {
          $totalLikes = 0;
        foreach ($s->chapters as $c) {
        $likes = Likes::where('liked_data', (string) $c->_id)->count();
            $c->likes_count = $likes; // correctly assign to chapter
            $totalLikes += $likes;    // accumulate
    }
    $s->likes_count = $totalLikes; // add this
    }
        return view('blog.story.manage',compact('story'));
    }

    

    
}

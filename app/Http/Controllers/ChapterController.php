<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\HtmlSanitizer; //sanitizer
use Illuminate\Support\Facades\Storage; //storage
use Illuminate\Support\Facades\Auth; //Auth
use App\Models\Story;
use App\Models\User;
use App\Models\Chapters;
use App\Models\Likes;
use App\Http\Requests\ChapterReq as req; // data validate input data

class ChapterController extends Controller
{
    public function index()
    {

    }

    // Chapter will be inserted when plus was clicked at the story's chapter section
    public function storeChapter(Request $req,$id)
    {
        $story = Story::findOrFail($id);

        $chapter = Chapters::create([
            'id_story' => (string) $story->_id,
            'title' => 'Entitled Chapter',
            'content' => '',
            'status' => 'draft',
        ]);
        $id_c = (string) $chapter->_id;

         // bro this is so stupid, the form remember the data, it will isnert when refresh
        // To Do: Fix this!
        // return view('blog.story.write',compact('chapter'));
        
        // update: done, i forgot i have an id saves lol
        return to_route('chapters.edit', $id_c);

        //  return to_route('chapters.edit', $id_c);
    }

        // Show the editor page // on detail story page for later
    public function edit($id)
    {
        $chapter = Chapters::findOrFail($id);
       
        return view('blog.story.write', compact('chapter'));

        
    }


    // update handler
    // tok the wok what teh fok
    public function updateChapter(req $req,$id)
    {
        $data = $req->validated();

        $chapter = Chapters::findOrFail($id);

        $chapter->title =  HtmlSanitizer::clean($data['title']);
        $chapter->content = HtmlSanitizer::clean($data['content']);
        $chapter->status =  HtmlSanitizer::clean($data['status']);

        // i like like this
        $chapter->save();

        return response()->json([
            'message' => 'Saved!'
        ]);
    }

    public function deleteChapter($id)
    {
        $chapter = Chapters::findOrFail($id);

        $chapter->delete();
         return response()->json([
            'message' => 'deleted!'
        ]);
    }

    public function showsChapter($id)
    {
    $chapter = Chapters::findOrFail($id);
    $story = Story::find($chapter->id_story);
    // new
    $liked = Likes::where('user_id', Auth::id())
    ->where('liked_data', $chapter->_id)
    ->exists();

        $allChapters = Chapters::where('id_story', $story->_id)->where('status','Published')
        ->orderBy('created_at', 'asc')
        ->get();
    // new
    $chapter_likesCount = Likes::where('liked_data', (string) $chapter->_id)->count();

    $currentIndex = $allChapters->search(function ($c) use ($id) {
        return (string) $c->_id === (string) $id;
    });

    $prev = $currentIndex > 0 ? $allChapters[$currentIndex - 1] : null;
    $next = $currentIndex < $allChapters->count() - 1 ? $allChapters[$currentIndex + 1] : null;

    return view('blog.story.read', compact('chapter', 'story', 'allChapters', 'prev', 'next','liked','chapter_likesCount'));
    }
}

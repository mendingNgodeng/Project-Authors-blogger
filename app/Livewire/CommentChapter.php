<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CommentChapters;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CommentChapter extends Component
{
   public $chapter_id;
    public $comment = '';

    public function post()
    {
        $this->validate([
            'comment' => 'required|string|max:500',
        ]);

        CommentChapters::create([
            'user_id' => Auth::id(),
            'chapter_id' => $this->chapter_id,
            'comment' => $this->comment,
        ]);

        $this->reset('comment');
        session()->flash('message', 'Comment posted!');
    }

    public function render()
    {
        return view('livewire.comment-chapter', [
            'comments' => \App\Models\CommentChapters::with('user')
                ->where('chapter_id', $this->chapter_id)
                ->latest()
                ->get(),
        ]);
    }

   
}

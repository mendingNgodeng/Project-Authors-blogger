<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Chapters;
use App\Models\Likes;
use App\Models\Comment;
use App\Models\CommentChapters;
use App\Models\Story;
use App\Models\Media;
use App\Models\Post;

use App\Http\Requests\ProfileUpdateRequest;
use App\Helpers\HtmlSanitizer; //sanitizer
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; //storage
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
    $user = $request->user(); // get user once
    $oldPic = $user->pic;
    //  Handle profile picture
    if ($request->hasFile('pic')) {
        // Check if old picture exists and delete it
        if ($oldPic) {
            $oldPath = str_replace('/storage/', '', $oldPic);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }else {
                logger("Old pic not found at: {$oldPath}");
            }
        }

        //  Store new picture
        $file = $request->file('pic');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('users', $filename, 'public');

        $user->pic = '/storage/' . $path;
    }

    $user->name = HtmlSanitizer::clean($request->input('name'));
    $user->username = HtmlSanitizer::clean($request->input('username'));
    $user->email = HtmlSanitizer::clean($request->input('email'));

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        if ($user->pic && file_exists(public_path($user->pic))) {
        unlink(public_path($user->pic));
        }
        $id = $user->_id;
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

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ViewsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


// Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->middleware(['auth', 'verified'])->name('dashboard');

    


Route::middleware(['auth','verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    

    // only used by admin
Route::middleware(['role.admin'])->group(function () {
    // Only routes here will check role
   

Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    // Users Route
Route::get('/users',[UsersController::class,'index'])->name('users.index');
//for ajax, hopefully works though
Route::get('/users/api', [UsersController::class, 'api'])->name('users.api'); //not used at all lol
Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');

Route::get('/users/add',[UsersController::class,'create'])->name('users.create');
Route::post('/users/store',[UsersController::class,'store'])->name('users.store');
Route::post('/users/storeAjax',[UsersController::class,'storeAjax'])->name('users.storeAjax');
Route::get('/users/{id}/edit',[UsersController::class,'edit'])->name('users.edit');
Route::get('/users/{id}/detail',[UsersController::class,'detail'])->name('users.detail');
Route::put('/users/{id}',[UsersController::class,'update'])->name('users.update');
Route::delete('/users/{id}/delete',[UsersController::class,'delete'])->name('users.delete');
// Route::post('/users/{id}/delete',[UsersController::class,'delete'])->name('users.delete');
// comments 
Route::get('/list_comments',[CommentController::class,'index'])->name('comments.index');
Route::delete('/comment/{id}/delete',[CommentController::class,'deleteComment'])->name('comment.delete');

});
// special admin access end

// Posts Route
Route::get('/posts',[PostsController::class,'index'])->name('posts.index');
Route::get('/posts/add',[PostsController::class,'create'])->name('posts.create');
Route::post('/posts/store',[PostsController::class,'store'])->name('posts.store');
Route::get('/posts/{id}/detail',[PostsController::class,'detail'])->name('posts.detail');
Route::get('/posts/{id}/edit',[PostsController::class,'edit'])->name('posts.edit');
Route::put('/posts/{id}',[PostsController::class,'update'])->name('posts.update');
Route::delete('/posts/{id}',[PostsController::class,'delete'])->name('posts.delete');

// stories only access by registeted users
Route::get('/stories/create',[StoryController::class,'AddStory'])->name('story.create');
Route::post('/stories',[StoryController::class,'storeStory'])->name('story.store');
Route::put('/stories/{id}',[StoryController::class,'updateStory'])->name('story.update');
Route::delete('/stories/{id}/delete',[StoryController::class,'deleteStory'])->name('story.delete');

// manage stories
Route::get('/stories/your_stories',[StoryController::class,'manage'])->name('story.manage');
Route::get('/stories/create/{id}',[StoryController::class,'AddStory'])->name('story.detail');
// manage chapter
Route::post('/chapter/{id}/write',[ChapterController::class,'storeChapter'])->name('chapters.store');
Route::get('/chapters/{id}/edit', [ChapterController::class, 'edit'])->name('chapters.edit');
Route::put('/chapters/{id}/update', [ChapterController::class, 'updateChapter'])->name('chapters.update');
Route::delete('/chapters/{id}', [ChapterController::class, 'deleteChapter'])->name('chapters.delete');
// manage like
Route::post('/like/{id}', [LikeController::class, 'like'])->name('like.toggle');



});
// read chapter
Route::get('/chapters/read/{id}', [ChapterController::class, 'showsChapter'])->name('chapters.read');

Route::get('/stories',[StoryController::class,'index'])->name('story.index');
Route::redirect('/', '/beranda');
Route::get('/beranda',[ViewsController::class,'index'])->name('posts.beranda');
Route::post('/beranda/{id}/{id_post}',[ViewsController::class,'comment'])->name('posts.comment');
Route::post('/comments', [ViewsController::class, 'store'])->name('comments.store');

require __DIR__.'/auth.php';

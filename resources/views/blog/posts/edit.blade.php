@extends('layouts.template')

@section('title', 'Welcome')

@section('content')

<!-- card body -->

    <!-- card header -->

    <!-- body -->
     <div class="border-3">
        <div class="col-span-1 lg:col-span-6">
  <h4 class="text-3xl text-gray-700 mb-5 flex items-center">Edit Post</h4>
  <div class="p-10 rounded-md shadow-md bg-white">
    <form action="{{ route('posts.update', (string) $post->_id) }}" method="post" enctype="multipart/form-data">
      @csrf
      @method('PUT')
   <div class="mb-6">
    <label class="block mb-3 text-gray-600" for="">Judul</label>
    <input
     type="text" name="title" class="border border-gray-500 rounded-md inline-block py-2 px-3 w-full text-gray-600 tracking-widest" value="{{ old('title',$post->title) }}"/>
       @error('title')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
   </div>

    <div class="mb-6">
    <label class="block mb-3 text-gray-600" for="">Desckripsi</label>
   <textarea name="content" id="" cols="80" rows="10">{{ old('content',$post->content) }}</textarea></textarea>
     @error('content')
      <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
     @enderror
   </div>

   <div class="mb-6">
    <label class="block mb-3 text-gray-600" for="">Upload Media (Or drag and Drop here)</label>
    <input
     type="file" name="media[]" multiple class="border border-gray-500 rounded-md sm:inline-block py-2 px-3 w-full text-gray-600 tracking-widest" />
      @error('pic')
      <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
     @enderror
   </div>

    <div class="mb-6">
    <label class="block mb-3 text-gray-600" for="">Category</label>
    <input
     type="text" name="category" class="border border-gray-500 rounded-md inline-block py-2 px-3 w-full text-gray-600 tracking-widest" value="{{ old('category',$post->category) }}" />
       @error('category')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
   </div>

    <div class="mb-6">
    <label class="block mb-3 text-gray-600" for="">Tags</label>
    <input
     type="text" name="tags" class="border border-gray-500 rounded-md inline-block py-2 px-3 w-full text-gray-600 tracking-widest" placeholder="tags (coma-separated)" value="{{ old('tags',$post->tags) }}"/>
       @error('tags')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
   </div>

   <div class="mb-6">
  <label class="block mb-3 text-gray-600" for="">Status</label>
  <select name="status" class="border border-gray-500 rounded-md py-2 px-3 w-full text-gray-600">
    <option value="published" {{ $post->status == 'published' ? 'selected' : ''}}>Published</option>
    <option value="draft" {{ $post->status == 'draft' ? 'selected' : ''}} >Draft</option>
  </select>
</div>
    <!-- <div class="w-2/3 px-3">
     <label class="block mb-3 text-gray-600" for="">Expiraion date</label>

     <div class="flex">
      <select class="border border-gray-500 rounded-md inline-block py-2 px-3 w-full text-gray-600 tracking-widest mr-6">
       <option>Month</option>
      </select>

      <select class="border border-gray-500 rounded-md inline-block py-2 px-3 w-full text-gray-600 tracking-widest">
       <option>Year</option>
      </select>

     </div> -->
    <!-- </div>
    <div class="w-1/3 px-3">
     <label class="block mb-3 text-gray-600" for="">CVC</label>
     <input type="tel" class="border border-gray-500 rounded-md inline-block py-2 px-3 w-full text-gray-600 tracking-widest"/>
    </div> -->
   
   <div class="mb-6 text-right">
    <span class="text-right font-bold"></span>
   </div>
   <div class="flex gap-3">
    <button class="w-full text-center px-4 py-3 bg-blue-500 rounded-md shadow-md text-white font-semibold">
     <i class="fa fa-paper-plane"></i> Save
    </button>

    <a href="{{ route('posts.index') }}" class="w-full text-center px-4 py-3 bg-gray-500 rounded-md shadow-md text-white font-semibold"><i class="fa fa-arrow-left"></i> Back </a>

    </form>
   </div>
  </div>
 </div>
</div>


@endsection

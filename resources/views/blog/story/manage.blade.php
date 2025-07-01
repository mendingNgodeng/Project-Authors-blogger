@extends('layouts.template_read')

@section('title', 'Story Time')

@section('content')
    <h2 class="text-2xl font-bold mb-4 text-center">Your Stories Dear Author  <span class="bg-blue-600 rounded p-1 text-white">{{Auth::user()->username}}</span></h2>

    <!-- one for mobile, two for tabet and three for desktop -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($story as $s)

            <div class="bg-white rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
                @if(isset($s->cover))
                <img src="{{ asset($s->cover) }}" alt="Image" class="w-full h-48 object-cover rounded">
                @else
                <div class="border-gray-800 bg-gray-700 text-white  h-60 text-center flex items-center justify-center">
                    No Cover
                </div>
                @endif
                <div class="p-4">
                    <h3 class="text-xl font-semibold text-gray-800 truncate">{{ $s->title }}</h3>
                    <p class="text-sm text-gray-600 mt-1"><strong>Author:</strong> {{ $s->author }}</p>
                    <p class="text-sm text-gray-600"><strong>Type:</strong> {{ $s->type }}</p>
                    <p class="text-sm text-gray-600"><strong>Status:</strong> {{ $s->status }}</p>
                    <p class="text-sm text-gray-600 mb-3"><strong>Genre:</strong> {{ $s->genre }}</p>
                    <p class="text-sm text-gray-600 mb-3"><strong>Likes:</strong> {{ $s->likes_count }}</p>

                    <a href="{{ route('story.detail', (string) $s->_id) }}" 
                       class="inline-block bg-yellow-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                       Details
                    </a>
                </div>
                
            </div>
          
        @empty
            <p class="text-gray-500 col-span-full text-center">You have no stories yet. lets make one now!</p>
        @endforelse
    </div>
@endsection

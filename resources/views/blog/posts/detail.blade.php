@extends('layouts.template')

@section('title', 'Post Detail')

@section('content')
<div class="bg-white shadow-md rounded p-6 max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>

    <p><strong>Posted by:</strong> {{ $post->poster }}</p>
    <p><strong>Role:</strong> {{ $post->role }}</p>
    <p><strong>Created at:</strong> {{ $post->created_at->format('d M Y, H:i') }}</p>
    <p><strong>Description:</strong> {{ $post->content }}</p>
    <hr class="my-4">

    <h2 class="text-xl font-semibold mb-2">Media</h2>
<!-- 
    @if($post->media->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($post->media as $media)
                <div class="border rounded p-2">
                    @if(Str::endsWith($media->url, ['.jpg', '.jpeg', '.png', '.gif', '.webp','.mp4','.mp3','.mkv']))
                        <img src="{{ asset($media->url) }}" alt="Media" class="w-full h-48 object-cover rounded">
                    @else
                        <a href="{{ asset($media->url) }}" target="_blank" class="text-blue-600 underline">
                            View File
                        </a>
                    @endif
                    <p class="text-sm mt-1 text-gray-600">{{ $media->type ?? 'unknown data type' }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-red-500 italic">No media uploaded.</p>
    @endif -->

    <!-- @if($post->media->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @foreach($post->media as $media)
            <div class="border rounded p-2">
                @php
                    $url = asset($media->url);
                    $type = strtolower(pathinfo($media->url, PATHINFO_EXTENSION));
                @endphp

                @if(in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    <img src="{{ $url }}" alt="Image" class="w-full h-48 object-cover rounded">
                @elseif(in_array($type, ['mp4', 'mkv', 'mov']))
                    <video controls class="w-full h-48 object-cover rounded" preload="none">
                        <source src="{{ $url }}" type="video/{{ $type }}">
                        Your browser does not support the video tag.
                    </video>
                @elseif(in_array($type, ['mp3', 'wav']))
                    <audio controls class="w-full" preload="none">
                        <source src="{{ $url }}" type="audio/{{ $type }}" >
                        Your browser does not support the audio tag.
                    </audio>
                @else
                    <a href="{{ $url }}" target="_blank" class="text-blue-600 underline">
                        View File
                    </a>
                @endif

                <p class="text-sm mt-1 text-gray-600">{{ $media->type ?? 'unknown data type' }}</p>
            </div>
        @endforeach
    </div>
@else
    <p class="text-red-500 italic">No media uploaded.</p>
@endif -->

@if($post->media->count() > 0)
    @php
        $images = $post->media->filter(fn($m) => in_array(strtolower(pathinfo($m->url, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']));
        $videos = $post->media->filter(fn($m) => in_array(strtolower(pathinfo($m->url, PATHINFO_EXTENSION)), ['mp4', 'mkv', 'mov']));
        $audios = $post->media->filter(fn($m) => in_array(strtolower(pathinfo($m->url, PATHINFO_EXTENSION)), ['mp3', 'wav']));
        $others = $post->media->filter(fn($m) => !in_array(strtolower(pathinfo($m->url, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'mkv', 'mov', 'mp3', 'wav']));
    @endphp

    {{-- Images --}}
    @if($images->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-4">
        @foreach($images as $media)
            <div class="border rounded p-2">
                <img src="{{ asset($media->url) }}" alt="Image" class="w-full h-48 object-cover rounded">
                <p class="text-sm mt-1 text-gray-600">Image</p>
            </div>
        @endforeach
    </div>
    @endif

    {{-- Videos --}}
    @if($videos->count() > 0)
    <div class="mb-4">
        @foreach($videos as $media)
            <div class="border rounded p-2 mb-4">
                <video controls class="w-full h-48 object-cover rounded" preload="none">
                    <source src="{{ asset($media->url) }}" type="video/{{ pathinfo($media->url, PATHINFO_EXTENSION) }}">
                </video>
                <p class="text-sm mt-1 text-gray-600">Video</p>
            </div>
        @endforeach
    </div>
    @endif

    {{-- Audio + First Image --}}
    @if($audios->count() > 0)
    <div class="mb-4">
        @foreach($audios as $media)
            <div class="border rounded p-2 mb-4">
                @if($images->isNotEmpty())
                    <img src="{{ asset($images->first()->url) }}" alt="Cover" class="w-full h-48 object-cover rounded mb-2">
                @endif
                <audio controls class="w-full" preload="none">
                    <source src="{{ asset($media->url) }}" type="audio/{{ pathinfo($media->url, PATHINFO_EXTENSION) }}">
                </audio>
                <p class="text-sm mt-1 text-gray-600">Audio</p>
            </div>
        @endforeach
    </div>
    @endif

    {{-- Other Files --}}
    @if($others->count() > 0)
    <div class="mt-4">
        @foreach($others as $media)
            <div class="border rounded p-2 mb-2">
                <a href="{{ asset($media->url) }}" target="_blank" class="text-blue-600 underline">View File</a>
                <p class="text-sm mt-1 text-gray-600">{{ $media->type ?? 'unknown type' }}</p>
            </div>
        @endforeach
    </div>
    @endif
@else
    <p class="text-red-500 italic">No media uploaded.</p>
@endif


    <div class="mt-6">
        <a href="{{ route('posts.index') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded">
            &larr; Back to Posts
        </a>
    </div>
</div>
@endsection

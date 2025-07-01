@extends('layouts.template')

@section('title', 'Blog Posts')

@section('content')


<div class="container  h-[95vh] overflow-auto grid lg:grid-cols-2 md:grid-cols-1 gap-4 p-4 relative">
    @foreach($post as $p)
    @if($p->status === 'published')
    <!-- card -->
    <div 
    x-data="viewpost()"
    @keydown.escape.window="close()"
    class=" flex-[3] w-full  border border-slate-400 rounded-lg p-4 shadow-md">
    <!-- card header -->
        <div class="border-1 flex gap-3">
        <div class="">
            @if(isset($p->user->pic))
        <img src="{{ asset($p->user->pic) }}" class="w-20 h-20 rounded-full" alt="User Image">
            @else
            <i class="fa fa-user"></i>
            @endif
        </div>
   
        <div class="">
            <h2 class="text-lg font-semibold">{{ isset($p->user->name) ? $p->user->name : 'user deleted' }}</h2>
            <p class="text-sm">{{$p->role}}</p>
            <p class="text-sm">{{ $p->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
    <!-- end card header -->

    <!-- card body  -->
        <div class="p-4 space-y-4 ">
        
            <div class="border-b pb-4">
                <h3 class="text-xl font-bold">{{$p->title}}</h3>
                <p class="text-gray-700">{{ Str::limit($p->content,100)}}</p>
               
                @auth
            <a href="#" @click="open()" class="text-blue-500 hover:underline">
        Baca Selengkapnya
    </a>@endauth

            </div>
            <!-- media here -->
            <div class="">
                <!-- <h1>Media here</h1> -->
                @if($p->media->count() > 0)
    @php
        $images = $p->media->filter(fn($m) => in_array(strtolower(pathinfo($m->url, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']));
        $videos = $p->media->filter(fn($m) => in_array(strtolower(pathinfo($m->url, PATHINFO_EXTENSION)), ['mp4', 'mkv', 'mov']));
        $audios = $p->media->filter(fn($m) => in_array(strtolower(pathinfo($m->url, PATHINFO_EXTENSION)), ['mp3', 'wav']));
        $others = $p->media->filter(fn($m) => !in_array(strtolower(pathinfo($m->url, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'mkv', 'mov', 'mp3', 'wav']));
    @endphp

    {{-- Images --}}
    @if($images->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-4">
        @foreach($images as $media)
            <div class="border rounded p-2">
                <img src="{{ asset($media->url) }}" alt="Image" loading="lazy" class=" w-full h-48 object-cover rounded">
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
    <!-- <p class="text-red-500 italic">No media uploaded.</p> -->
@endif
            </div>
            <hr>
            <!-- like and share -->
            <div class="border-b pb-4 flex justify-center gap-5">
                <p>
                   Like <i class="fa fa-thumbs-up"></i>
                </p>
                <p>
                    share <i class="fa fa-share-alt"></i>
                </p>
                <p>
                    Follow<i class="fa fa-user-plus"></i>
                </p>
            </div>

            
            <!-- comment here -->
           
            <!-- Bisa ditambahkan post lainnya -->
        </div>
        @include('blog.user_view.modalPost')


    <!-- end card body  -->

    <!-- modal -->
<!-- end modal -->
    </div>
  

    @endif
    @endforeach
  
</div>

<!-- Desktop side share (OUTSIDE the container) -->
<div class="hidden md:block fixed top-24 right-4 w-48 border border-gray-200 rounded-lg shadow-md p-4 bg-white z-40">
    <h3 class="text-md font-semibold mb-2">Bagikan ke:</h3>
    <ul class="space-y-2">
        <li><a href="#" class="text-blue-600 hover:underline">Facebook</a></li>
        <li><a href="#" class="text-blue-400 hover:underline">Twitter</a></li>
        <li><a href="#" class="text-pink-500 hover:underline">Instagram</a></li>
    </ul>
</div>

<!-- Mobile share (floating button style) -->
<div class="md:hidden fixed bottom-4 right-4 bg-white border border-gray-300 rounded-lg shadow-lg p-3 z-50">
    <h3 class="text-sm font-semibold mb-1">Bagikan:</h3>
    <div class="flex gap-3 text-sm">
        <a href="#" class="text-blue-600"><i class="fa fa-facebook"></i></a>
        <a href="#" class="text-blue-400"><i class="fa fa-twitter"></i></a>
        <a href="#" class="text-pink-500"><i class="fa fa-instagram"></i></a>
    </div>
</div>

@auth
<script>
 function viewpost(){
 
    return {
        show:false,
        postId: null,
        echoChannel: null,

        open(id) {
            this.show = true;
            this.postId = id;

            this.echoChannel = Echo.channel(`post.${this.postId}`);

            this.echoChannel.listen('.comment.sent', (e) => {
                if (e.comment.user_id !== window.userId) {
                    const commentBox = document.createElement("div");
                    commentBox.className = "border rounded p-2 mb-2";
                    commentBox.innerHTML = `
                        <div class="flex gap-3">
                            <img src="${e.comment.user?.pic ?? '/storage/users/guest_.png'}" class="w-10 h-10 rounded-full">
                            <div>
                                <p class="text-sm text-gray-800">
                                    <strong>${e.comment.user?.name ?? 'Guest'}</strong>
                                    ${e.comment.content}
                                </p>
                                <p class="text-xs text-gray-400">${new Date(e.comment.created_at).toLocaleString()}</p>
                            </div>
                        </div>
                        <p class="text-sm">${e.comment.comment}</p>
                    `;
                    document.querySelector('#commentList').prepend(commentBox);
                }
            });
        },

        close() {
            this.show = false;
            if (this.echoChannel) {
                Echo.leave(`post.${this.postId}`);
                this.echoChannel = null;
            }
        }
 }    
 }
</script>
@endauth
@endsection


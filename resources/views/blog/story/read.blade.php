@extends('layouts.template_read')

@section('title', $chapter->title)

@section('content')
<div x-data="readerSettings()" 
x-init="load()" 
 x-on:keydown.escape.window="open = false"
 x-bind:class="{ 'bg-gray-900 text-white': darkMode, 'bg-white text-black': !darkMode }"

class="min-h-screen flex justify-center  transition-colors duration-300">
    <div class="max-w-3xl w-full py-3">

        <!-- Title -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">{{ $chapter->title }}</h1>
            <p class="text-sm text-gray-500 mb-4">
                From story: <strong>{{ $story->title }}</strong><br>
                Created: {{ $chapter->created_at->format('d M Y') }} |
                Updated: {{ $chapter->updated_at->format('d M Y') }}
            </p>
            <p>Likes: {{$chapter_likesCount}}</p>

            <!-- Chapter Dropdown -->
            <select class="border rounded p-2 mb-4" onchange="if (this.value) window.location.href = this.value;"
              :class="darkMode ? 'bg-gray-800 text-white border-gray-600' : 'bg-white text-black border-gray-300'">
                @foreach($allChapters as $c)
                    <option value="{{ route('chapters.read', $c->_id) }}" {{ $c->_id == $chapter->_id ? 'selected' : '' }}>
                        {{ $c->title }}
                    </option>
                @endforeach
            </select>

        <!-- Font Controls -->
<div class="flex flex-wrap gap-3 justify-center mb-2">
    <!-- Font Size -->
    <div class="flex items-center gap-2">
        <span class="text-sm">Size:</span>
        <button @click="decreaseFont()" class="px-2 py-1 border rounded">A-</button>
       <span class="text-sm" x-text="fontSize + 'px'"></span>
        <button @click="increaseFont()" class="px-2 py-1 border rounded">A+</button>
    </div>

    <!-- Font Style -->
    <div class="flex items-center gap-2">
        <span class="text-sm">Font:</span>
        <select class="w-26 rounded-lg" x-model="fontFamily" @change="save()"
          :class="darkMode ? 'bg-gray-800 text-white border-gray-600' : 'bg-white text-black border-gray-300'"
        class="border rounded p-1">
            <option value="serif">Serif</option>
            <option value="sans-serif">Sans</option>
            <option value="monospace">Monospace</option>
        </select>
    </div>
<!-- Dark Mode Toggle -->
<div class="flex items-center gap-2">
    <span class="text-sm">Theme:</span>
    <button @click="toggleDark()" class="px-2 py-1 border rounded">
        <span x-text="darkMode ? '🌙 Dark' : '☀️ Light'"></span>
    </button>
</div>
    
    <!-- Text Alignment -->
    <div class="flex items-center gap-2 ">
        <span class="text-sm">Align:</span>
        <select class="w-26 rounded-lg" x-model="textAlign" @change="save()" class="border rounded p-1"
        :class="darkMode ? 'bg-gray-800 text-white border-gray-600' : 'bg-white text-black border-gray-300'">
            <option value="left">Left</option>
            <option value="center">Center</option>
            <option value="right">Right</option>
            <option value="justify">Justify</option>
        </select>
    </div>
</div>


<div class="fixed top-1/2 right-4 transform -translate-y-1/2 flex flex-col gap-3 z-50">
@if(Auth::check())
    <!-- Like Button -->
    <div x-data="likeToggle('{{ $chapter->_id }}', {{ $liked ? 'true' : 'false' }})">
        <button 
            @click="toggleLike"
            class="text-xl px-4 py-2 rounded shadow bg-white"
            :class="liked ? 'text-red-600' : 'text-gray-400'"
        >
            <span x-text="liked ? '❤️ Liked' : '🤍 Like'"></span>
        </button>
    </div>
@endif
    <!-- Comment Button -->
    <button 
        @click="$dispatch('open-comment-modal')" 
        class="bg-blue-600 text-white px-4 py-2 rounded-full shadow-md hover:bg-blue-700"
    >
        💬
    </button>
</div>


        <!-- Navigation -->
        <div class="flex justify-between text-blue-600 mb-1">
            @if($prev)
                <a href="{{ route('chapters.read', $prev->_id) }}" class="hover:underline">&larr; {{ $prev->title }}</a>
            @else
                <span></span>
            @endif

            @if($next)
                <a href="{{ route('chapters.read', $next->_id) }}" class="hover:underline">{{ $next->title }} &rarr;</a>
            @else
                <span></span>
            @endif
        </div>

        <!-- Chapter Content -->
       <div class=" max-w-none mb-10 whitespace-pre-wrap text-left"
     :style="`font-size: ${fontSize}px; font-family: ${fontFamily}; text-align: ${textAlign}`">
    {!! $chapter->content !!} 
    <!-- yes it raw -->
</div>

        <!-- Navigation Bottom -->
        <div class="flex justify-between text-blue-600 mb-6">
            @if($prev)
                <a href="{{ route('chapters.read', $prev->_id) }}" class="hover:underline">&larr; {{ $prev->title }}</a>
            @else
                <span></span>
            @endif

            @if($next)
                <a href="{{ route('chapters.read', $next->_id) }}" class="hover:underline">{{ $next->title }} &rarr;</a>
            @else
                <span></span>
            @endif
        </div>

        <div class="text-center">
            <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline">← Back</a>
        </div>
    </div>

    @include('blog.story.modalComment')
</div>

<!-- Alpine Component -->
<script>
function readerSettings() {
    return {
        fontSize: 16, // base = 16px
        fontFamily: 'serif',
        textAlign: 'justify',
        darkMode: false,


        increaseFont() {
            this.fontSize += 2;
            this.save();
        },
        decreaseFont() {
            if (this.fontSize > 10) this.fontSize -= 2;
            this.save();
        },
           toggleDark() {
            this.darkMode = !this.darkMode;
            this.save();
        },
        save() {
            localStorage.setItem('readerFontSize', this.fontSize);
            localStorage.setItem('readerFontFamily', this.fontFamily);
            localStorage.setItem('readerTextAlign', this.textAlign);
            localStorage.setItem('readerDarkMode', this.darkMode);

        },
       
        load() {
            const size = localStorage.getItem('readerFontSize');
            const family = localStorage.getItem('readerFontFamily');
             const align = localStorage.getItem('readerTextAlign');
                const dark = localStorage.getItem('readerDarkMode');
            if (size) this.fontSize = parseInt(size);
            if (family) this.fontFamily = family;
            if (align) this.textAlign = align;
            if (dark) this.darkMode = dark === 'true';
        }
    };
}


function likeToggle(contentId, initiallyLiked) {
    return {
        liked: initiallyLiked,
        async toggleLike() {
            try {
                const res = await fetch(`/like/${contentId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await res.json();
                this.liked = data.liked;
            } catch (err) {
                alert('Something went wrong or you are not logged in.');
            }
        }
    }
}

</script>
@endsection

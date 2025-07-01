
<div class="bg-white rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
    @if(isset($s->cover))
    <img src="{{ asset($s->cover) }}" alt="Image" loading="lazy" class=" w-full h-48 object-cover rounded">
    @else
    <div class="border-gray-800 bg-gray-700 text-white lg:w-48 h-48 text-center flex items-center justify-center">
        No Cover
    </div>
    @endif
    <div class="p-4">
        <h3 class="text-xl font-semibold text-gray-800 truncate">{{ $s->title }}</h3>
        <p class="text-sm text-gray-600 mt-1"><strong>Author:</strong> {{ $s->author }}</p>
        <p class="text-sm text-gray-600"><strong>Type:</strong> {{ $s->type }}</p>
        <p class="text-sm text-gray-600 mb-3"><strong>Genre:</strong> {{ $s->genre }}</p>

        @php
        $storyData = [
            '_id' => (string) $s->_id,
            'title' => $s->title,
            'summary' => $s->summary,
            'cover' => $s->cover,
            'created_at' => $s->created_at,
            'updated_at' => $s->updated_at,
            'chapters' => $s->chapters->map(fn($c) => [
                '_id' => (string) $c->_id,
                'title' => $c->title,
                'content' => $c->content,
                'status' => $c->status,
                'created_at' => $c->created_at,
                'updated_at' => $c->updated_at,
                'likes_count' => $c->likes_count,
            ])
        ];
        @endphp

        <button @click='showModal(@json($storyData))'
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
            View Story
        </button>
    </div>
</div>

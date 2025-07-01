
    @forelse($story as $s)
        @include('blog.story.storycard', ['s' => $s])
    @empty
       <div class=" text-center py-6 text-gray-500 text-lg">
        No stories available.
    </div>
    @endforelse

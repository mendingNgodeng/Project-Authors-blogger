@extends('layouts.template_read')

@section('title', 'Story Time')

@section('content')
<div x-data="storyModal()">
    <h2 class="text-2xl font-bold mb-4 text-center">Welcome to Story page!</h2>
<div class="flex gap-3 justify-center py-3">

    <div class="w-full">
        <input type="text" id="searchInput" class="w-full p-2 border rounded-lg" placeholder="search stories...">
    </div>

    <select name="category" id="categorySelect" class="w-40 p-2 border rounded-lg">
        <option value="title">Title</option>
        <option value="author">Author</option>
        <option value="type">Type</option>
        <option value="genre">Genre</option>
    </select>
</div>

    <!-- one for mobile, two for tabet and three for desktop -->
<div id="storyGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- made partial for search -->
       @include('blog.story.storyList')
</div>

@include('blog.story.modalPreview')
</div>

<script>
function storyModal() {
    return {
        open: false,
        selectedStory: null,
        showModal(data) {
            this.selectedStory = data;
            this.open = true;
        }
    }
}

// search feature yeeeaahhhhhhh
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');
    const storyGrid = document.getElementById('storyGrid');

    function debounce(func, delay = 300) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

  async function searchStories() {
    const query = searchInput.value;
    const category = categorySelect.value;

    if (!storyGrid) return console.warn('#storyGrid not found'); // just cehck

    try {
        const response = await fetch(`/stories?query=${encodeURIComponent(query)}&category=${category}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const html = await response.text();
        storyGrid.innerHTML = html;
    } catch (err) {
        console.error("Failed to fetch stories", err);
    }
}

    const debouncedSearch = debounce(searchStories);

    searchInput.addEventListener('keyup', debouncedSearch);
    categorySelect.addEventListener('change', debouncedSearch);

</script>
@endsection

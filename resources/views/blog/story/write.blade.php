@extends('layouts.template')

@section('title', 'Write Chapter')

@section('content')
<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    #chapterEditorPage {
        height: 100vh;
        width: 90vw;
        display: flex;
        flex-direction: column;
      
    }

    #editor {
        height: 80vh;
        width: 100%;
        background-color: white;
    }

    .ql-container {
        height: 100%;
    }

    .ql-editor {
        font-size: 1.125rem; /* text-lg */
        line-height: 1.75rem; /* relaxed */
        min-height: 100%;
    }

    form {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    form > div {
        width: 100%;
        max-width: none;
    }
</style>
<div id="chapterEditorPage" class="">
    <h2 class="text-3xl font-bold mb-2 text-center">
        Chapter Title: <span class="text-blue-600">{{ $chapter->title }}</span>
    </h2>

    <form id="chapterForm" method="POST" >
        @csrf
        <input type="hidden" name="story_id" value="{{ $chapter->id_story }}">
        <input type="hidden" name="content" id="content">

        <div class="flex items-center gap-4 mb-2">
           
            <input type="text" name="title" id="title"
                value="{{ $chapter->title }}"
                class="flex-1 border px-4 py-2 rounded "
                placeholder="Chapter Title" />
                <p id="titleError" class="text-sm text-red-600 mt-1 hidden "></p>
               

            <select name="status" id="status" class="border px-4 py-2 rounded">
                <option value="draft" {{ $chapter->status === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="Published" {{ $chapter->status === 'Published' ? 'selected' : '' }}>Published</option>
            </select>

            <!-- <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Save Chapter
            </button> -->

            <a href="{{ route('story.detail', (string) $chapter->id_story)}}" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700">back</a>
        </div>

        <div id="editor"></div>
    </form>
</div>
@endsection

@push('scripts')
    <!-- Quill CDN -->
    <link href="{{asset('quill.snow.css')}}" rel="stylesheet">
    <script src="{{ asset('js/quill.js') }}"></script>

    <script>
        const quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Start writing your story here...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['clean']
                ]
            }
        });

        const form = document.querySelector('form');
        form.onsubmit = function () {
            document.querySelector('#content').value = quill.root.innerHTML;
        };


    // Set content from DB
    quill.root.innerHTML = @json($chapter->content ?? '');

    const formContent = document.querySelector('#chapterForm');
    const contentInput = document.querySelector('#content');
    const statusIndicator = document.createElement('p');
    statusIndicator.className = "text-sm text-gray-500 mt-2";
    statusIndicator.textContent = "Idle";

    formContent.appendChild(statusIndicator); // Attach to form bottom

    let timeoutId = null;

    function autoSave() {
        const title = document.querySelector('#title').value;
        const status = document.querySelector('#status').value;
        const content = quill.root.innerHTML;

        // Set status to saving
        statusIndicator.textContent = "Saving...";

        fetch("{{ route('chapters.update', $chapter->_id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                _method: 'PUT', // simulate PUT
                title: title,
                status: status,
                content: content
            })
        })
       .then(async response => {
    if (!response.ok) {
        if (response.status === 422) {
            const data = await response.json();
            showErrors(data.errors);
            statusIndicator.textContent = "⚠️ Validation error";
        } else {
            throw new Error('Unexpected error');
        }
    } else {
        clearErrors(); // If successful, clear old errors
        statusIndicator.textContent = "Saved ✔️";
    }
})
.catch(error => {
    console.error(error);
    statusIndicator.textContent = "❌ Failed to save";
});
    }

    function debounceSave() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(autoSave, 1000); // waits 1s after last change
    }

    // Bind auto-save on editor & fields
    quill.on('text-change', debounceSave);
    document.querySelector('#title').addEventListener('input', debounceSave);
    document.querySelector('#status').addEventListener('change', debounceSave);

    // If user still clicks the Save button
    form.onsubmit = function (e) {
        contentInput.value = quill.root.innerHTML;
    };

    function showErrors(errors) {
    const titleError = document.getElementById('titleError');

    if (errors.title) {
        titleError.textContent = errors.title[0];
        titleError.classList.remove('hidden');
    } else {
        titleError.textContent = '';
        titleError.classList.add('hidden');
    }
}

function clearErrors() {
    const titleError = document.getElementById('titleError');
    titleError.textContent = '';
    titleError.classList.add('hidden');
}
    </script>
@endpush

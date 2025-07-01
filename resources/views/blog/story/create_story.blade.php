@extends('layouts.template')

@section('title', 'Story Time')

@section('content')
<!-- for trigger detail -->
@php
$isUpdate = isset($story)
@endphp


<div class="flex flex-col items-center justify-center">
    <div class="">
    <h2 class="text-2xl font-bold mb-4 text-center">Unleashed Your Imagination</h2>
        <div>
        <div class="bg-blue-400 rounded-md text-center">
            <h3 class="p-2 "><span class=" text-gray-700">It's better to try and failed many times than just not doing it </span></h3>
        </div>
        </div>
  
    <div class="flex gap-3 justify-center mt-3 items-center">

    <!-- story input -->
           <div class="bg-white rounded-lg shadow-lg p-6 border h-[95vh]">
            <h3 class="text-lg font-semibold mb-4">Create Your Story</h3>
            <form id="storyForm" enctype="multipart/form-data" action="{{ route('story.store') }}" method="POST" class="space-y-4 px-10">
                @csrf
                <input type="hidden" name="story_id" id="story_id" value="{{ $isUpdate ? $story->_id : '' }}">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="title" value="{{ $isUpdate ? $story->title : '' }}" class="mt-1 block w-full border rounded-md p-2" >
                    <p class="text-red-500 text-sm mt-1" id="error-title"></p>
                </div>

                <div>
                    <label for="summary" class="block text-sm font-medium text-gray-700">Summary</label>
                    <!-- <textarea rows="10" cols="" type="text" name="summary" id="summary" class="mt-1 block w-full border rounded-md p-2" >{{ $isUpdate ? $story->summary : '' }}</textarea>  -->
                        <input type="hidden" name="summary" id="summary">

                        <!-- DA Quill  -->
                         <div class="max-w-2xl w-full">
                        <div id="quill-summary" class="bg-white p-2 rounded-md border min-h-[200px] w-full">
                            {!! $isUpdate ? $story->summary : '' !!}
                        </div>
                        </div>
                    <p class="text-red-500 text-sm mt-1" id="error-summary"></p>
                </div>

                <div>
                    <label for="cover" class="block text-sm font-medium text-gray-700">Cover Image</label>
                    <input type="file" name="cover" id="cover" class="mt-1 block w-full border rounded-md p-2">
                    @if($isUpdate)
                    <p class="text-sm mt-1">{{ $story->cover }}</p>
                    @endif
                    <p class="text-red-500 text-sm mt-1" id="error-cover"></p>
                </div>

    <div class="flex gap-3">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <select name="type" id="type" class="mt-1 border rounded-md p-2 w-48">
                        <option value="Original" {{ $isUpdate && $story->type === 'Original' ? 'selected' : '' }} >Original</option>
                        <option value="Fanfic" {{ $isUpdate && $story->type === 'Fanfic' ? 'selected' : '' }}>Fanfic</option>
                    </select>
                    <p class="text-red-500 text-sm mt-1" id="error-select"></p>
                </div>

                <div>
                    <label for="completed" class="block text-sm font-medium text-gray-700">Complete</label>
                    <select name="completed" id="completed" class="mt-1 border rounded-md p-2 w-48">
                        <option value="OnGoing" {{ $isUpdate && $story->type === 'OnGoing' ? 'selected' : '' }}>OnGoing</option>
                        <option value="Completed" {{ $isUpdate && $story->type === 'Completed' ? 'selected' : '' }} >Completed</option>
                    </select>
                    <p class="text-red-500 text-sm mt-1" id="error-select"></p>
                </div>
</div>
                <div>
                    <label for="genre" class="block text-sm font-medium text-gray-700">Genre</label>
                    <input type="text" name="genre" id="genre" class="mt-1 block w-full border rounded-md p-2" placeholder="e.g. Action, Fantasy" value="{{ $isUpdate ? $story->genre : '' }}"  >
                    <p class="text-red-500 text-sm mt-1" id="error-genre"></p>

                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full border rounded-md p-2">
                        <option value="Draft" {{ $isUpdate && $story->status === 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Published" {{ $isUpdate && $story->status === 'Published' ? 'selected' : '' }}>Published</option>
                    </select>
                    <p class="text-red-500 text-sm mt-1" id="error-status"></p>

                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    Submit Story
                </button>

                <button type="button" id="deleteBtn" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 {{ $isUpdate ? '' : 'hidden' }}"> Delete Story
                </button>
            </form>
        </div>
    <!-- Chapters Select -->
        <div class="bg-white rounded-lg shadow-lg p-6 h-[95vh] overflow-y-auto">
            <div class="flex items-center flex-col">
                    <h3 class="text-lg font-semibold mb-4">Chapters</h3>
                    <!-- <a type="button" id="ChapterBtn" class=" p-2 bg-black text-white py-2 rounded {{ $isUpdate ? '' : 'hidden' }}"> <i class="fa fa-plus "></i> -->
                     <!-- @if($isUpdate) -->
           
                     <!-- <a href="{{ route('chapters.store', $story->_id) }}" id="ChapterBtn"
   onclick="event.preventDefault(); document.getElementById('add-chapter-form').submit();"
   class="p-2 bg-black text-white py-2 rounded {{ $isUpdate ? '' : 'hidden' }}">
   <i class="fa fa-plus"></i>
</a> -->
<a href="#" id="ChapterBtn"
   onclick="event.preventDefault(); document.getElementById('add-chapter-form').submit();"
   class="p-2 bg-black text-white py-2 rounded {{ $isUpdate ? '' : 'hidden' }}">
   <i class="fa fa-plus"></i>
</a>

<form id="add-chapter-form" action="{{ route('chapters.store', (string) $story->_id) }}" method="POST" class="hidden">
    @csrf
</form>

<!-- @endif -->
                </div>
            @if(isset($chapter) && $chapter->count())
                <ul class="space-y-4">
                    @php $no=1; @endphp
                    @foreach($chapter as $chapter)
                        <li class="border rounded-lg shadow-sm p-4 hover:shadow-lg list-{{ (string) $chapter->_id}}">
                            <h2>Chapter {{ $no++ }}:</h2>
                            <div class="">
                                <h3 class="p-2 bg-blue-500 text-white rounded-lg font-bold">{{$chapter->title}}</h3>
                                   <div class="mt-1 block w-full border rounded-md p-2">
                                Word Count: <p>{{Str::of($chapter->content)->wordCount()}}</p>
                                    </div>

                                <div class="mt-1 block w-full border rounded-md p-2">
                                    Created: <p>{{$chapter->created_at}}</p>
                                </div>
                                <div class="mt-1 block w-full border rounded-md p-2">                                
                                Updated: <p>{{$chapter->updated_at}}</p>
                                </div>
                                <div class="mt-1 block w-full border rounded-md p-2 ">                                
                                Status: <p class="{{$chapter->status === 'draft' ? 'bg-red-600' : 'bg-green-600'}} text-center p-1 rounded-md text-white ">{{$chapter->status}}</p>
                                </div>

                            </div>
                            <div class="flex gap-3">
                            <div class="mt-4">
                                <a href="{{ route('chapters.edit', (string) $chapter->_id)}}" class=" p-2 bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Edit</a>
                            </div>

                              <div class="mt-4">
                                <a href="" class="delete-chapter-btn p-2 bg-red-600 text-white py-2 rounded hover:bg-red-700" 
                                data-id="{{ (string) $chapter->_id }}">Delete</a>
                            </div>
                            </div>
                           
                        </li>
                    @endforeach
                </ul>
            @else
               <p class="text-gray-500 mt-3 no-chapters-message {{ isset($chapter) && $chapter->count() ? 'hidden' : '' }}">
                No Chapters were made yet.
                </p>
            @endif
        </div>
      
        
    </div>

</div>
<!-- endcontainer -->
</div>
 <link href="{{asset('quill.snow.css')}}" rel="stylesheet">
    <script src="{{ asset('js/quill.js') }}"></script>
<script>
// Custom HR for toolbar
    const BlockEmbed = Quill.import('blots/block/embed');

    class HrBlot extends BlockEmbed {
        static create() {
            const node = super.create();
            return node;
        }
    }

    HrBlot.blotName = 'hr';
    HrBlot.tagName = 'hr';

    Quill.register(HrBlot);
// 
const quill = new Quill('#quill-summary', {
    theme: 'snow',
    placeholder: 'Write your story summary here...',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],     
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
             ['hr'],
            ['clean']
        ],
        
    }
});


$(document).ready(function () {
    $('#storyForm').on('submit', function (e) {
        e.preventDefault();
        $('#summary').val(quill.root.innerHTML);
        let form = new FormData(this);
        let storyId = $('#story_id').val();
        let method = storyId ? 'POST' : 'POST'; // always POST
        let url = storyId
            ? "/stories/" + storyId
            : "{{ route('story.store') }}";
            // i forgot i used REST so override method put for update here
            // optionally i can just put the route to post but i like to suffer i suppose
         if (storyId) {
            form.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            method: method,
            data: form,
            processData: false,
            contentType: false,
            success: function (res) {
                Swal.fire({
                    icon: 'success',
                    title: storyId ? 'Story updated!' : 'Story created!',
                    text: res.message,
                    timer:1500
                });
                // good hack. for the Swal to appear hehe
                setTimeout(() => {
                    window.location.reload();
                }, 1600);

                const id_story = res.id_story;
                // switch to update mode when new data inserted just created
                if (!storyId && res.story) {
                        $('#story_id').val(id_story);
                        $('#title').val(res.story.title);
                        $('#summary').val(res.story.summary);
                        $('#type').val(res.story.type);
                        $('#completed').val(res.story.completed);
                        $('#genre').val(res.story.genre);
                        $('#status').val(res.story.status);
                        
                    $('#ChapterBtn').removeClass('hidden');
                    $('#deleteBtn').removeClass('hidden');
                     window.history.pushState({}, '', '/stories/create/' + id_story); 
                    // so the button add chapter wont updated the id
                    //  $('#add-chapter-form').attr('action', '/chapters/' + id_story+'/write');

                    // above not work for whatever reason so i just used this for now i guess
                    // window.location.reload()
                }

            },
            error: function (xhr) {
                  // Clear old error messages
    $('#storyForm .text-red-500').text('');
    $('#storyForm input, #storyForm textarea, #storyForm select').removeClass('border-red-500');

    if (xhr.status === 422) {
        const errors = xhr.responseJSON.errors;

        // Loop through each error field and show the error message
        for (const field in errors) {
            const errorElement = $('#error-' + field);
            const inputElement = $('#' + field);
            // it worked and i want to sleep
            errorElement.text(errors[field][0]); // Show first error
            inputElement.addClass('border-red-500'); // Highlight field
        }
    }
            }
        });
    });
});


$('#deleteBtn').on('click', function () {
    const id = $('#story_id').val();
    if (!id) return;

    Swal.fire({
        title: 'Are you sure?',
        text: 'This will delete the story and all chapters related to it permanently.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/stories/" + id+"/delete",
                method: "POST",
                data: {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    Swal.fire('Deleted!', res.message, 'success');

                    // Clear form
                    $('#storyForm')[0].reset();
                    $('#story_id').val('');
                    $('#title').val('');
                        $('#summary').val('');
                        $('#type').val('Original');
                        $('#Completed').val('OnGoing');
                        $('#genre').val('');
                        $('#status').val('Draft');

                    // Hide delete after delete
                    $('#deleteBtn').addClass('hidden');
                    $('#ChapterBtn').addClass('hidden');

                     // Remove all chapters from the DOM
                    $('ul.space-y-4').empty();
                    $('.no-chapters-message').removeClass('hidden');
                     window.history.pushState({}, '', '/stories/create/');

                },
                error: function (xhr) {
                    Swal.fire('Oops!', xhr.responseJSON?.message ?? 'Something went wrong.', 'error');
                }
            });
        }
    });



});


// delete chapter
$(document).on('click', '.delete-chapter-btn', function (e) {
    e.preventDefault();
    const chapterId = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: 'This will delete the chapter permanently.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/chapters/' + chapterId,
                method: 'POST', // or DELETE if your route supports it
                data: {
                    _method: 'DELETE',
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    Swal.fire('Deleted!', res.message, 'success');

                    // Remove the chapter DOM element
                    $('.list-' + chapterId).remove();

                    // Check if there are no chapters left
                    if ($('ul.space-y-4').children().length === 0) {
                        $('.no-chapters-message').removeClass('hidden');
                    }
                },
                error: function (xhr) {
                    Swal.fire('Oops!', xhr.responseJSON?.message ?? 'Something went wrong.', 'error');
                }
            });
        }
    });
});
</script>
@endsection

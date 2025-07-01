<div x-show="show"   
x-transition:leave.duration.300ms 
  x-cloak
  @keydown.escape.window="close()"
  @click.self="close()"
  x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
 x-transition:enter.duration.300ms>
    <div class="bg-white rounded-lg p-6 max-w-2xl w-full relative max-h-[90vh] overflow-y-auto min-h-[300px] sm:min-h-[400px]">
        <button @click="close()" class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">
            &times;
        </button>
         <div class="border-1 flex gap-3">
        <div class="">
            @if(isset($p->user->pic))
        <img src="{{ asset($p->user->pic) }}" class="w-20 h-20 rounded-full"  loading="lazy" alt="User Image">
            @else
            <i class="fa fa-user"></i>
            @endif
        </div>
   
        <div class="">
            <h2 class="text-lg font-semibold">{{ isset($p->user->name) ? $p->user->name : 'user deleted'}}</h2>
            <p class="text-sm">{{$p->role}}</p>
            <p class="text-sm">{{ $p->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
    <!-- end card header -->

    <!-- card body  -->
        <div class="p-4 space-y-4 ">
            <!-- Example blog post -->
            <div class="border-b pb-4">
                <h3 class="text-xl font-bold">{{$p->title}}</h3>
                <p class="text-gray-700">{{ $p->content }}</p>
               
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
    <div class="grid grid-cols-1 gap-4 mb-4">
        @foreach($images as $media)
            <div class="border rounded p-2 w-full h-auto">
                <img src="{{ asset($media->url) }}" alt="Image" class=" object-cover rounded ">
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
                <video controls class="w-full h-48 object-cover rounded aspect-video" preload="none">
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
                    <img src="{{ asset($images->first()->url) }}" alt="Cover" loading="lazy" class="object-cover rounded mb-2">
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
           
                <form id="commentForm-{{ $p->_id }}">
                @csrf
                <div class="comments-container flex gap-3">

                
                <input type="text" placeholder="Comment" class="mb-6 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" name="comment" id="commentInput-{{ $p->_id }}" autocomplete="off">
                <div class="">
                    <button type="submit" class="p-3 bg-blue-500 hover:bg-blue-400 rounded-lg hover:shadow-md"><i class="fa fa-paper-plane"></i></button>
                </div>
                </div>
             </form>

             <!-- comment here -->

              <!-- Comments -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Komentar:</h3>
            <!-- a hack so the input comment wont inserted into all of the posted available like wth?! -->
            <div id="commentList-{{ $p->_id }}">
            @forelse($p->comment as $comment)
                <div class="border rounded p-2 mb-2">
                    <div class="flex gap-3">
                    @if(isset($comment->user->pic))
                    <img src="{{ asset($comment->user->pic) }}" loading="lazy" class="w-10 h-10 rounded-full object-cover" alt="User Image">
                    @else
                    <img src="{{ 'storage/users/guest_.png' }}" class="w-10 h-10 rounded-full object-cover" alt="User Image">                    
                    @endif 
                    <div class="">
                    <p class="text-sm text-gray-800"><strong>{{ $comment->user?->name ?? 'Guest'  }}</strong> {{ $comment->content }}</p>
                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($comment->created_at)->format('d M Y, H:i') }}</p>
                    </div>

                </div>
                <p class="text-sm">{{$comment->comment}}</p>
                </div>
            @empty
                <p class="text-sm text-gray-500 italic">Belum ada komentar.</p>
            @endforelse
        </div>
        </div>
            <!-- Bisa ditambahkan post lainnya -->
        </div>
    </div>

</div>

@auth
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // hack cus i dont know what to do, pray to allah that this will be working and so that i can sleep
        // update: i didn't sleep at all but got it working at least
        const postId="{{ $p->_id }}";
        const form = document.getElementById('commentForm-'+postId);
        const input = document.getElementById('commentInput-'+postId);
        const list = document.getElementById('commentList-'+postId);

       form.addEventListener('submit', function(e) {
    e.preventDefault();

    fetch('{{ route("posts.comment", ["id" => Auth::id(), "id_post" => $p->_id]) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            comment: input.value
        })
    })
    .then(res => res.json())
    .then(data => {
        input.value = '';

        renderComment({
            postId:postId,
            pic: data.pic,
            user_name: data.user_name,
            comment: data.comment.comment,
            created_at: data.comment.created_at
        });
    })
    .catch(error => {
        console.error('Error submitting comment:', error);
    });
});
    });

function renderComment({ postId, pic, user_name, comment, created_at }) {
    // const postId="{{ $p->_id }}";
    const html = `
        <div class="border rounded p-2 mb-2">
            <div class="flex gap-3">
                <img src="${pic ?? '/storage/users/guest_.png'}" loading="lazy" class="w-10 h-10 rounded-full" alt="User Image">
                <div>
                 
                <p class="text-sm text-gray-800"><strong>${user_name ?? 'Guest'}</strong> </p>
                    <p class="text-xs text-gray-400">${created_at}</p>
                </div>
                </div>
                <p>${comment}</p>
        </div>
    `;
    document.getElementById('commentList-'+postId).insertAdjacentHTML('afterbegin', html);
}
</script>
@endauth
<script>
document.addEventListener("DOMContentLoaded", () => {
    const postId = "{{ $p->_id }}";

    if (typeof window.Echo !== 'undefined') {
        window.Echo.channel(`post.${postId}`)
            .listen('.comment.sent', (e) => {
                if (e.comment.user_id !== window.userId) {
                    renderComment({
                        postId: postId,
                        pic: e.comment.user?.pic,
                        user_name: e.comment.user?.name,
                        comment: e.comment.comment,
                        created_at: new Date(e.comment.created_at).toLocaleString()
                    });
                }
            });
    }
});
</script>


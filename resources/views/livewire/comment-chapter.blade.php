<div>
    @if (session()->has('message'))
        <div class="mb-2 text-green-600 text-sm">
            {{ session('message') }}
        </div>
    @endif

    <textarea wire:model.defer="comment" rows="3" class="w-full p-2 border rounded" placeholder="Leave a comment..."></textarea>
    <button wire:click="post" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Post</button>

    <div class="mt-4 space-y-4">
        @forelse($comments as $comment)
            <div class="border-t pt-2">

            <div class="flex gap-3">
                  @if(isset($comment->user->pic))
        <img src="{{ asset($comment->user->pic) }}" class="w-10 h-10 rounded-full" alt="User Image">
            @else
            <i class="fa fa-user"></i>
            @endif
                <div class="">
                <p class="font-bold text-gray-800">{{ $comment->user->username ?? 'Guest' }}</p>
                <p class="text-sm text-gray-800">{{ $comment->created_at->format('d M Y H:i') }}</p>
                </div>
</div>


                <p class="text-gray-600 text-sm">{{ $comment->comment }}</p>
            </div>
        @empty
            <p class="text-sm text-gray-500">No comments yet.</p>
        @endforelse
    </div>
</div>

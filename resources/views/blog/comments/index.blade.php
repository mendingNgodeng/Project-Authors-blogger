@extends('layouts.template')

@section('title', 'Comment Data')

@section('content')

<!-- Alpine modal -->
<div x-data="CommentForm()" x-init="window.CommentFormRef = $data" @keydown.escape.window="close()">

    <!-- card header -->
    <div class="bg-gray-300 flex sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0 p-2 rounded-lg">
        <h2 class="text-3xl font-bold p-2">Data Comments</h2>
    </div>

    <!-- body -->
    <div class="w-full">
        <div class="min-w-[90px] overflow-x-auto">
            @if(Auth::user()->_id)
            <table id="dataTables" class="w-full overflow-x-auto divide-y divide-gray-200">
                <thead class="bg-gray-100 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Username</th>
                        <th class="px-6 py-3">Post</th>
                        <th class="px-6 py-3">Comment</th>
                        <th class="px-6 py-3">Role</th>
                        <th class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($comments as $s)
                    <tr data-id="{{ (string) $s->_id }}" class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                        <td class="px-6 py-4">{{ $no++ }}</td>
                        <td class="px-6 py-4">{{ $s->user->username }}</td>
                        @if(isset($s->post->title))
                        <td class="px-6 py-4">{{ $s->post->title}}</td>
                        @else
                        <td><span class="bg-red-500 text-white p-2 rounded-lg">Post Deleted</span></td>
                        @endif
                        <td class="px-6 py-4">{{ Str::limit($s->comment, 10) }}</td>
                        <td class="px-6 py-4">{{ $s->user->role }}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-3">
                               
                               @php
$commentData = [
    'user' => [
        'username' => $s->user->username,
        'name' => $s->user->name,
        'role' => $s->user->role,
    ],
    'post' => [
        'title' => $s->post->title ?? 'post deleted',
    ],
    'comment' => $s->comment,
    'created_at' => \Carbon\Carbon::parse($s->created_at)->format('d M Y, H:i'),
];
@endphp

<button 
    onclick='CommentFormRef.open(@json($commentData))' 
    class="text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-2 rounded">
    <i class="fa fa-eye"></i>
</button>

             <button 
    onclick="deleteComment('{{ $s->_id }}', this)"  
    class="text-white bg-red-500 hover:bg-red-600 px-3 py-2 rounded">
    <i class="fa fa-trash"></i>
    </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        
        @include('blog.comments.modalDetail')

    </div> {{-- ey --}}
</div> {{--  x-data wrapper end hereeeeee--}}

<script>
function CommentForm() {
    return {
        show: false,
        comment: {},
        open(data) {
            console.log('Opening modal with data:', data);
            this.comment = data;
            this.show = true;
        },
        close() {
            this.show = false;
            this.comment = {};
        },
        // delete
          deleteComment(commentID, el) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    Swal.fire({
        title: 'Hapus user?',
        text: "Data tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch(`/comment/${commentID}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({ _method: 'DELETE' })
                });
                console.log('Raw response:', response); // wanna see the response
                const result = await response.json();
                console.log('Parsed result:', result); // wanna see the result
               
                if (!response.ok) {
                    Swal.fire('Gagal!', result.message || 'Gagal menghapus data.', 'error');
                    return;
                }

                // Remove the row from DOM
                el.closest('tr').remove();

                Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
            } catch (err) {
                console.error(err);
                Swal.fire('Error!', 'Terjadi kesalahan saat menghapus.', 'error');
            }
        }
        });
    },
    init() {
    window.deleteComment = this.deleteComment.bind(this);
    }
    }
}
</script>

@endsection

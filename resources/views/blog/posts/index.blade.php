@extends('layouts.template')

@section('title', 'Posts')

@section('content')

<!-- Alpine modal -->
<div x-data="postForm()" x-init="window.postFormRef = $data" @keydown.escape.window="close()">
    <!-- card header -->
<!-- <div class="container  h-[95vh] overflow-auto grid md:grid-cols-1 p-4 relative"> -->

    <div class="bg-gray-300 flex sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0 p-2 rounded-lg">
        <h2 class="text-3xl font-bold p-2">Data Posts</h2>

        <div class="flex items-center p-2">
          <a href="#" @click="open()" class="color-white bg-green-600 rounded-lg hover:bg-green-500 p-2 flex items-center">
        Tambah Data
          </a>
        </div>
    </div>

    <!-- body -->
   <div class="min-w-[90px]  max-w-7xl mx-auto mt-2 overflow-x-auto">
    <!-- Inner wrapper with min width to enable scrolling -->
    <div class="" >
        @if(Auth::user()->_id)
        <table id="dataTables" class="divide-y divide-gray-200">
            <thead class="bg-gray-100 text-left text-xs font-medium text-gray-700 uppercase tracking-wider w-full">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Username</th>
                    <th class="px-6 py-3">Title</th>
                    <th class="px-6 py-3">Media</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($post as $s)
                <tr data-id="{{ (string) $s->_id }}" class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                    <td class="px-6 py-2">{{ $no++ }}</td>
                    <td class="px-6 py-2">{{ $s->poster }}</td>
                    <td class="px-6 py-2">{{ $s->title }}</td>
                  <td class="px-6 py-2">
                    @if($s->media && $s->media->count() > 0)
                    {{ $s->media->count() }} file{{ $s->media->count() > 1 ? 's' : '' }}
                     @else
                     <span class="text-red-500 italic">No media uploaded</span>
                    @endif
                    </td>
                    <td class="px-6 py-2">{{ $s->role }}</td>
                    <td class="px-6 py-2">
                        <div class="flex gap-2">
                           
                            <!-- <form action=" {{route('posts.delete',(string) $s->_id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-white bg-red-500 hover:bg-red-600 px-3 py-2 rounded" onclick="return confirm('Delete this data with username {{ $s->username }} ?')">
                                <i class="fa fa-trash"></i>
                                </button>
                            </form> -->
                                                         <button 
    onclick="deletePost('{{ $s->_id }}', this)"  
    class="text-white bg-red-500 hover:bg-red-600 px-3 py-2 rounded">
    <i class="fa fa-trash"></i>
    </button>

                            <!-- <a href="{{ route('posts.edit', (string) $s->_id) }}" class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded"><i class="fa fa-edit"></i></a> -->

                            @php
                        $postData = [
                            '_id' => (string) $s->_id,
                            'title' => $s->title,
                            'content' => $s->content,
                            'category' => $s->category,
                            'tags' => $s->tags,
                            'status' => $s->status,
                            'role' => $s->role,
                            'poster' => $s->poster,
                            'media' => $s->media,
                        ];
                        @endphp
                        <a href="#" 
                            onclick='postEditOpen(@json($postData))'
                            class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded">
                            <i class="fa fa-edit"></i>
                        </a>

                             <a href="{{ route('posts.detail', (string) $s->_id) }}" class="text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-2 rounded"><i class="fa-solid fa-info"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

@include('blog.posts.modalAdd')
@include('blog.posts.modalEdit')

</div>
<!-- </div> -->


<script>
window.postEditOpen = function(postJson) {
    let post;
    try {
        post = typeof postJson === 'string' ? JSON.parse(postJson) : postJson;
    } catch (e) {
        console.error("Failed to parse post data", e);
        return;
    }

    if (window.postFormRef && typeof window.postFormRef.editPostOpen === 'function') {
        window.postFormRef.editPostOpen(post);
    } else {
        console.error("postFormRef not available");
    }
};
</script>

<script>
function postForm() {
    return {
        show: false,
        editShow: false,
        form: {
            title: '',
            content: '',
            category: '',
            tags: '',
            status: 'published',
            media: []
        },
        editForm:{
            title: '',
            content: '',
            category: '',
            tags: '',
            role: '',
            poster: '',
            status: 'published',
            media: []
        },
    editPostOpen(post) {
        this.editForm = {
      
        _id: post._id,
        title: post.title ?? '',
        content: post.content ?? '',
        category: post.category ?? '',
        tags: post.tags ?? '',
        status: post.status ?? 'draft',
        media: post.media ?? [],
        poster: post.poster ?? '',
        role: post.role ?? '',
      
        rowEl: document.querySelector(`tr[data-id="${post._id}"]`)
    };
    this.editShow = true;
},

        errors: {},

        open() {
            this.reset();
            this.show = true;
        },
         openShow() {
            this.show = true;
        },

        close() {
            this.show = false;
            this.editShow = false;
        },

        reset() {
            this.form = {
                title: '',
                content: '',
                category: '',
                tags: '',
                status: 'published',
                media: []
            };
            this.errors = {};
        },

        handleFileUpload(event) {
            this.form.media = Array.from(event.target.files);
        },
        handleEditFileUpload(event) {
    this.editForm.media = Array.from(event.target.files);
},


        async store() {
            this.errors = {};
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const formData = new FormData();

            formData.append('title', this.form.title);
            formData.append('content', this.form.content);
            formData.append('category', this.form.category);
            formData.append('tags', this.form.tags);
            formData.append('status', this.form.status);

            // Append multiple files
            this.form.media.forEach((file, index) => {
                formData.append('media[]', file);
            });

            try {
                const response = await fetch("{{ route('posts.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok) {
                    this.errors = result.errors || {};
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Post berhasil ditambahkan!',
                    showConfirmButton: false,
                    timer: 3000
                });

                // update post list DOM here, not yet full CRUD
                // Get the table body
const tableBody = document.querySelector("tbody");

// Find the next row number
const currentRowCount = tableBody.querySelectorAll("tr").length;
const nextNo = currentRowCount + 1;

// Generate media info note: finally works
const mediaCount = result.data.media?.length ?? 0;
const mediaInfo = mediaCount > 0
    ? `${mediaCount} file${mediaCount > 1 ? 's' : ''}`
    : `<span class="text-red-500 italic">No media uploaded</span>`;
const id_post = result.id_post;
// Generate row
tableBody.insertAdjacentHTML("beforeend", `
<tr class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
    <td class="px-6 py-2">${nextNo}</td>
    <td class="px-6 py-2">${result.data.poster ?? 'Unknown'}</td>
    <td class="px-6 py-2">${result.data.title}</td>
    <td class="px-6 py-2">${mediaInfo}</td>
    <td class="px-6 py-2">${result.data.role ?? '-'}</td>
    <td class="px-6 py-2">
                
        <div class="flex gap-2">
           <button onclick="deletePost('${id_post}', this)"  
                class="text-white bg-red-500 hover:bg-red-600 px-3 py-2 rounded">
                <i class="fa fa-trash"></i>
            </button>

            
                        <a href="#"
                     onclick='postEditOpen(${JSON.stringify({
                                _id: result.id_post,
                                title: result.data.title,
                                content: result.data.content,
                                category: result.data.category,
                                tags: result.data.tags,
                                status: result.data.status,
                                media: result.data.media,
                                poster: result.data.poster,
                                role: result.data.role,
                            })})'
                            class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded">
                            <i class="fa fa-edit"></i>
                        </a>


            <a href="/posts/${id_post}/detail" class="text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-2 rounded"><i class="fa-solid fa-info"></i></a>
        </div>
    </td>
</tr>
`);
                // 
                this.reset();
                this.close();
            } catch (err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal menyimpan!',
                    text: 'Terjadi kesalahan.',
                });
            }
        },

    // delete post
    deletePost(PostId, el) {
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
                const response = await fetch(`/posts/${PostId}`, {
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
    window.deletePost = this.deletePost.bind(this);
    },

    async updatePost() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formData = new FormData();
    formData.append('_method', 'PUT');

    formData.append('title', this.editForm.title);
    // formData.append('_id', this.editForm._id);
    formData.append('content', this.editForm.content);
    formData.append('category', this.editForm.category);
    formData.append('tags', this.editForm.tags);
    formData.append('status', this.editForm.status);

    // if (this.editForm.media?.length) {
    //     this.editForm.media.forEach((file, index) => {
    //         formData.append('media[]', file);
    //     });
    // }
    // yeah i forgot to check is the media is even exist at all...which trigger 422 errors, stupid fu-
    if (this.editForm.media?.length && this.editForm.media[0] instanceof File) {
    this.editForm.media.forEach(file => {
        formData.append('media[]', file);
    });
}

//     console.log('Sending FormData:');
// for (let pair of formData.entries()) {
//     console.log(pair[0]+ ': ' + pair[1]);
// }
    try {
        const response = await fetch(`/posts/${this.editForm._id}`, {
            
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
            },
            body: formData
        });
        console.log({
    title: this.editForm.title,
    _id: this.editForm._id,
    content: this.editForm.content,
    tags: this.editForm.tags,
    category: this.editForm.category,
    poster: this.editForm.poster,
    status: this.editForm.status,
    role: this.editForm.role,
    media: this.editForm.media,
});

        const result = await response.json();

        if (!response.ok) {
            this.errors = result.errors || {};
            return;
        }

        Swal.fire({
            icon: 'success',
            title: 'Post berhasil diupdate!',
            showConfirmButton: false,
            timer: 3000
        });

        const updatedPost = result.data;
        const row = this.editForm.rowEl;
        const id_post =  result.id_post;
        if (row) {
            row.innerHTML = `
                <td class="px-6 py-2">Updated</td>
                <td class="px-6 py-2">${updatedPost.poster}</td>
                <td class="px-6 py-2">${updatedPost.title}</td>
                <td class="px-6 py-2">
                    ${updatedPost.media && updatedPost.media.length > 0
                        ? `${updatedPost.media.length} file${updatedPost.media.length > 1 ? 's' : ''}`
                        : '<span class="text-red-500 italic">No media uploaded</span>'
                    }
                </td>
                <td class="px-6 py-2">${updatedPost.role ?? '-'}</td>
                <td class="px-6 py-2">
                    <div class="flex gap-2">
                        
                        <button onclick="deletePost('${id_post}', this)"
                            class="text-white bg-red-500 hover:bg-red-600 px-3 py-2 rounded">
                            <i class="fa fa-trash"></i>
                        </button>

                        <a href="#"
                            onclick='postEditOpen(${JSON.stringify({
                                _id: id_post,
                                title: updatedPost.title,
                                content: updatedPost.content,
                                category: updatedPost.category,
                                tags: updatedPost.tags,
                                status: updatedPost.status,
                                media: updatedPost.media,
                                poster: updatedPost.poster,
                                role: updatedPost.role,
                            })})'
                            class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded">
                            <i class="fa fa-edit"></i>
                        </a>


                        <a href='/posts/${id_post}/detail'
                            class="text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-2 rounded">
                            <i class="fa-solid fa-info"></i>
                        </a>
                    </div>
                </td>
            `;
        }

        this.editShow = false;

    } catch (err) {
        console.error(err);
        Swal.fire('Error!', 'Gagal update post.', 'error');
    }
}

    };
}
</script>

@endsection

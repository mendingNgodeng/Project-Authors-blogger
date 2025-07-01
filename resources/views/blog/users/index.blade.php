@extends('layouts.template')

@section('title', 'Data Users')

@section('content')

<!-- card body -->

<div x-data="userForm()" x-init="window.userFormRef = $data" @keydown.escape.window="close()">
<!-- <div class="container  h-[95vh] overflow-auto grid md:grid-cols-1 relative"> -->

    <!-- for alpine -->
     <div class="">
        <div class="bg-gray-300 flex sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0 p-2 rounded-lg">
            <h2 class="text-3xl font-bold p-2">Data Users</h2>

        <div class="flex items-center p-2">
        <!-- <a href="{{ route('users.create') }}" class="color-white bg-green-600 rounded-lg hover:bg-green-500 p-2 flex items-center">Tambah Data</a> -->

        <a href="#" @click="open()" class="color-white bg-green-600 rounded-lg hover:bg-green-500 p-2 flex items-center">
        Tambah Data
        </a>
      
      </div>
    </div>
    </div>

    <!-- body -->
   <div class="w-full overflow-x-auto">
    <!-- Inner wrapper with min width to enable scrolling -->
    <div class="min-w-[80px] ">
        <table id="dataTables" class="w-full overflow-x-auto divide-y divide-gray-200">
            <thead class="bg-gray-100 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">PIC</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Username</th>
                    <th class="px-6 py-3">E-mail</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                @php $no = 1; @endphp
                @foreach($user as $s)
               
                <tr data-id="{{ (string) $s->_id }}" class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                    <td class="px-6 py-2">{{ $no++ }}</td>
                    <td class="px-6 py-2">
                        @if($s->pic)
                            <p class="bg-green-300 flex items-center p-2 rounded-lg">Picture uploaded</p>
                        @else
                            <p class="bg-red-300 flex items-center p-2 rounded-lg">No Picture Uploaded</p>
                        @endif
                    </td>
                    <td class="px-6 py-2">{{ $s->name }}</td>
                    <td class="px-6 py-2">{{ $s->username }}</td>
                    <td class="px-6 py-2">{{ $s->email }}</td>
                    <td class="px-6 py-2">{{ $s->role }}</td>
                    <td class="px-6 py-2"> 
                        @if($s->isOnline())
                          <p class="bg-green-300 flex items-center p-2 rounded-lg">Online</p>
                        @else
                          <p class="bg-red-300 flex items-center p-2 rounded-lg">Offline</p>
                        @endif
                    </td>
                    <td class="px-6 py-2">
                        <div class="flex gap-2">
                           
                            <!-- <form action=" {{route('users.delete',(string) $s->_id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-white bg-red-500 hover:bg-red-600 px-3 py-2 rounded" onclick="return confirm('Delete this data with username {{ $s->username }} ?')">
                                <i class="fa fa-trash"></i>
                                </button>
                            </form> -->

                            <!-- <a  href="{{ route('users.edit', (string) $s->_id) }}" class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded"><i class="fa fa-edit"></i></a> -->

                            <!-- this thing not even triggered for whaytever reason -->
                             <!-- for some reason this one is out side of the scope of userForm() -->
                              <!-- remindder to fixxxxxxx MORROW  ORNING -->
                            <!-- working now yippeeee -->
                            @php
                            $userData = [
                                '_id' => (string) $s->_id,
                                'name' => $s->name,
                                'username' => $s->username,
                                'email' => $s->email,
                                'role' => $s->role
                            ];
                            @endphp

                            <a href="#"
                            onclick='userEditOpen(@json($userData))'
                            class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded">
                            <i class="fa fa-edit"></i>
                            </a>
                            


                             <!-- <a href="{{ route('users.detail', (string) $s->_id) }}" class="text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-2 rounded"><i class="fa-solid fa-info"></i></a> -->

                             <button 
    onclick="deleteUser('{{ $s->_id }}', this)"  
    class="text-white bg-red-500 hover:bg-red-600 px-3 py-2 rounded">
    <i class="fa fa-trash"></i>
    </button>

                        </div>
                    </td>
                </tr>

               
                @endforeach
            </tbody>
        </table>
   



    <!-- modal tambah data -->
    <!-- used alpine.js recommend from a friend, simple used -->
            <div  x-show="show" >
            @include('blog.users.modalAdd')
            </div>
    <!-- end modal tambah -->

     <!-- modal edit, also why the in the bloody hell haven't you work properly like the ogtehr one?!-->
     <!-- im sleepy but goddamn im too stubborn to sleep until this modal shows and for god sake, i have many more assignment, please help -->
            <div  x-show="editShow" x-cloak>
            @include('blog.users.modalEdit')
            </div>
    <!-- end modal edit -->

    </div> </div>
<!-- </div> -->

<!-- whatever the hell this is  -->
<script>
window.userEditOpen = function(userJson) {
    let user;
    try {
        user = typeof userJson === 'string' ? JSON.parse(userJson) : userJson;
    } catch (e) {
        console.error("Failed to parse user data", e);
        return;
    }

    if (window.userFormRef && typeof window.userFormRef.editOpen === 'function') {
        window.userFormRef.editOpen(user);
    } else {
        console.error("userFormRef not available");
    }
};
</script>
<!-- Ajax crud here -->


<script>
function userForm() {
    return {
        show: false,
        editShow: false,
        form: {
            name: '',
            username: '',
            password: '',
            email: '',
            role: '',
            status: '',
            pic: null
        },
        editForm: {
            _id: '',
            name: '',
            username: '',
            password: '',
            email: '',
            role: '',
            pic: null
        },
        errors: {},
        open() {
             console.log("Modal insert was called");
            this.reset();
            this.show = true;
        },
        close() {
            this.show = false;
            this.editShow = false;
        },
        reset() {
            this.form = {
                name: '',
                username: '',
                password: '',
                email: '',
                role: '',
                status: '',
                pic: null
            };
            this.errors = {};
        },
        handleFileUpload(event) {
            this.form.pic = event.target.files[0];
        },
        editHandleFileUpload(event) {
            this.editForm.pic = event.target.files[0];
        },
        editOpen(userJson) {
           console.log("editOpen() called");
           
    let user;
    try {
        // Try to parse if it's a string
        // update yes it fuckin works
        user = typeof userJson === 'string' ? JSON.parse(userJson) : userJson;
    } catch (e) {
        console.error("Failed to parse user data", e);
        return;
    }
            this.errors = {};
            this.editForm = { ...user };
            this.editShow = true;
        },
        editClose() {
            this.editShow = false;
        },
        async store() {
            this.errors = {};
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const formData = new FormData();
            formData.append('name', this.form.name);
            formData.append('username', this.form.username);
            formData.append('password', this.form.password);
            formData.append('email', this.form.email);
            formData.append('role', this.form.role);

            if (this.form.pic) {
                formData.append('pic', this.form.pic);
            }

            try {
                const response = await fetch("{{ route('users.storeAjax') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
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
                    title: 'User berhasil ditambahkan!',
                    showConfirmButton: false,
                    timer: 3000
                });
// Dont forget to put the DOM in here 
const tbody = document.querySelector("#dataTables tbody");

const user = result.data; // controller returns `return response()->json(['data' => $data_users]);`
const id_user = result.id_user; // controller returns `return response()->json(['id_user' => $data_users->_id]);`
console.log(user._id);
const newRow = document.createElement("tr");
newRow.className = "bg-white divide-y divide-gray-200 text-sm text-gray-700";
newRow.setAttribute("data-id", id_user);

newRow.innerHTML = `
    <td class="px-6 py-2">New</td>
    <td class="px-6 py-2">
        ${user.pic ? 
            '<p class="bg-green-300 flex items-center p-2 rounded-lg">Picture uploaded</p>' :
            '<p class="bg-red-300 flex items-center p-2 rounded-lg">No Picture Uploaded</p>'
        }
    </td>
    <td class="px-6 py-2">${user.name}</td>
    <td class="px-6 py-2">${user.username}</td>
    <td class="px-6 py-2">${user.email}</td>
    <td class="px-6 py-2">${user.role}</td>
    <td class="px-6 py-2">
        <p class="bg-red-300 flex items-center p-2 rounded-lg">Offline</p>
    </td>
    <td class="px-6 py-2">
        <div class="flex gap-2">
            <a href="#"
                onclick='userEditOpen(${JSON.stringify({
                    _id: id_user,
                    name: user.name,
                    username: user.username,
                    email: user.email,
                    role: user.role
                })})'
                class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded">
                <i class="fa fa-edit"></i>
            </a>

            <button onclick="deleteUser('${id_user}', this)"  
                class="text-white bg-red-500 hover:bg-red-600 px-3 py-2 rounded">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </td>
`;

tbody.appendChild(newRow);

this.reset();
this.close();

                // Optionally: reload, or update DOM
                // dom updated 22 mei
            } catch (err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan.',
                    text: 'Gagal menyimpan data.',
                });
            }
        },
        async updateUser() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('_id', this.editForm._id);
            formData.append('name', this.editForm.name);
            formData.append('username', this.editForm.username);
            formData.append('password', this.editForm.password);
            formData.append('email', this.editForm.email);
            formData.append('role', this.editForm.role);
            if (this.editForm.pic) {
                formData.append('pic', this.editForm.pic);
            }

            try {
                const response = await fetch(`/users/${this.editForm._id}`, {
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
                    title: 'User berhasil diupdate!',
                    showConfirmButton: false,
                    timer: 3000
                });

              // Find the existing row by data-id
const updatedUser = result.data; //return from data update json
const row = document.querySelector(`tr[data-id="${this.editForm._id}"]`);

if (row) {
    row.innerHTML = `
        <td class="px-6 py-2">Updated</td>
        <td class="px-6 py-2">
            ${updatedUser.pic ?
                '<p class="bg-green-300 flex items-center p-2 rounded-lg">Picture uploaded</p>' :
                '<p class="bg-red-300 flex items-center p-2 rounded-lg">No Picture Uploaded</p>'}
        </td>
        <td class="px-6 py-2">${updatedUser.name}</td>
        <td class="px-6 py-2">${updatedUser.username}</td>
        <td class="px-6 py-2">${updatedUser.email}</td>
        <td class="px-6 py-2">${updatedUser.role}</td>
        <td class="px-6 py-2">
            <p class="bg-green-300 flex items-center p-2 rounded-lg">Online</p>
        </td>
        <td class="px-6 py-2">
            <div class="flex gap-2">
                <a href="#"
                    onclick='userEditOpen(${JSON.stringify({
                        _id: this.editForm._id,
                        name: updatedUser.name,
                        username: updatedUser.username,
                        email: updatedUser.email,
                        role: updatedUser.role
                    })})'
                    class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded">
                    <i class="fa fa-edit"></i>
                </a>

                <button onclick="deleteUser('${this.editForm._id}', this)"  
                    class="text-white bg-red-500 hover:bg-red-600 px-3 py-2 rounded">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </td>
    `;
}
                this.editClose();

            } catch (err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan.',
                    text: 'Gagal mengupdate data.',
                });
            }
        },
        deleteUser(userId, el) {
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
                const response = await fetch(`/users/${userId}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({ _method: 'DELETE' })
                });
            
                const result = await response.json();
                
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
    window.deleteUser = this.deleteUser.bind(this);
    }

    };
}
</script>
@endsection
  <!-- <script>
    document.addEventListener('alpine:init', () => {
        console.log('✅ Alpine initialized');
    });
</script> -->


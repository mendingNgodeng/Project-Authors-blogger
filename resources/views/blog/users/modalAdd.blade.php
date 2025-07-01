<div x-show="show" x-cloak @keydown.escape.window="close()" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
    <div @click.away="close()" class="bg-white rounded-lg p-6 w-full max-w-2xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Tambah Data User</h2>
                <input type="hidden" x-model="form._id" class="w-full border px-3 py-2 rounded">

        <div class="grid grid-cols-2 gap-4">
            <!-- Name -->
            <div>
                <label class="block mb-1 font-medium">Nama</label>
                <input type="text" x-model="form.name" class="w-full border px-3 py-2 rounded">
                <p class="text-red-500 text-sm mt-1" x-show="errors.name" x-text="errors.name"></p>
            </div>

            <!-- Username -->
            <div>
                <label class="block mb-1 font-medium">Username</label>
                <input type="text" x-model="form.username" class="w-full border px-3 py-2 rounded">
                <p class="text-red-500 text-sm mt-1" x-show="errors.username" x-text="errors.username"></p>
            </div>

            
            <!-- pass -->
            <div>
                <label class="block mb-1 font-medium">Password</label>
                <input type="text" x-model="form.password" class="w-full border px-3 py-2 rounded">
                <p class="text-red-500 text-sm mt-1" x-show="errors.password" x-text="errors.password"></p>
            </div>

            <!-- Email -->
            <div>
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" x-model="form.email" class="w-full border px-3 py-2 rounded">
                <p class="text-red-500 text-sm mt-1" x-show="errors.email" x-text="errors.email"></p>
            </div>

            <!-- Role -->
            <div>
                <label class="block mb-1 font-medium">Role</label>
                <select x-model="form.role" class="w-full border px-3 py-2 rounded">
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">admin</option>
                    <option value="user">user</option>
                </select>
                <p class="text-red-500 text-sm mt-1" x-show="errors.role" x-text="errors.role"></p>
            </div>

      
            <!-- Picture Upload (Optional) -->
            <div>
                <label class="block mb-1 font-medium">Upload Foto (Opsional)</label>
                <input type="file" @change="handleFileUpload($event)" class="w-full border px-3 py-2 rounded">
                <p class="text-red-500 text-sm mt-1" x-show="errors.pic" x-text="errors.pic"></p>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-2 mt-6">
            <button @click="close()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Tutup</button>
            <button @click="store()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </div>
</div>
<!-- <script>
function userForm() {
    return {
        show: false,
          editShow: false, // for modalEdit
        form: {
            name: '',
            username: '',
            email: '',
            role: '',
            status: '',
            pic: null
        },
        errors: {},
        users:[], // all user data here

        open() {
            this.reset();
            this.show = true;
        },

        close() {
            this.show = false;
        },

        reset() {
            this.form = {
                name: '',
                username: '',
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

               
                // update user list via DOM after add the data option 1
//     //             document.querySelector("tbody").insertAdjacentHTML("beforeend", `
//     // <tr>
//     //     <td>${result.data.name}</td>
//     //     <td>${result.data.username}</td>
//     //     <td>${result.data.email}</td>
//     //     <td>${result.data.role}</td>
//     // </tr>
// `);

const tableBody = document.querySelector("#dataTables");

// Find the next row number
const currentRowCount = tableBody.querySelectorAll("tr").length;
const nextNo = currentRowCount + 1;

// Generate pic display
const picHtml = result.data.pic
    ? `<p class="bg-green-300 flex items-center p-2 rounded-lg">Picture uploaded</p>`
    : `<p class="bg-red-300 flex items-center p-2 rounded-lg">No Picture Uploaded</p>`;

const role = result.data.role ?? 'user';

// Add new row
tableBody.insertAdjacentHTML("beforeend", `
    <tr class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
        <td class="px-6 py-2">${nextNo}</td>
        <td class="px-6 py-2">${picHtml}</td>
        <td class="px-6 py-2">${result.data.name}</td>
        <td class="px-6 py-2">${result.data.username}</td>
        <td class="px-6 py-2">${result.data.email}</td>
        <td class="px-6 py-2">${role}</td>
        <td class="px-6 py-2">
            <p class="bg-red-300 flex items-center p-2 rounded-lg">Offline</p>
        </td>
        <td class="px-6 py-2">
            <div class="flex gap-2">
                <form action="/users/${result.data._id}" method="post">
                    <input type="hidden" name="_token" value="${token}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="text-white bg-red-500 hover:bg-red-600 px-3 py-2 rounded" onclick="return confirm('Delete this data with username ${result.data.username} ?')">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>

                <a href="/users/${result.data._id}/edit" class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded"><i class="fa fa-edit"></i></a>
                <a href="/users/${result.data._id}/detail" class="text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-2 rounded"><i class="fa-solid fa-info"></i></a>
            </div>
        </td>
    </tr>
`);

  

// option 2 bind with alpine.js
    // this.users.push(result.data);
this.reset();
this.close();
            } catch (err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan.',
                    text: 'Gagal menyimpan data.',
                });
            }
        }
    };
}
</script> -->



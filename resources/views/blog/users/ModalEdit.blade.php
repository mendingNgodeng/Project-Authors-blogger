<div x-show="editShow" x-cloak @keydown.escape.window="editClose()" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
    <div @click.away="editClose()" class="bg-white rounded-lg p-6 w-full max-w-2xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Edit Data User</h2>

     <input type="hidden" x-model="editForm._id" class="w-full border px-3 py-2 rounded">
        <!-- Nama -->
        <div>
            <label class="block mb-1 font-medium">Nama</label>
            <input type="text" x-model="editForm.name" class="w-full border px-3 py-2 rounded">
            <p class="text-red-500 text-sm mt-1" x-show="errors.name" x-text="errors.name"></p>
        </div>

        <!-- Username -->
        <div>
            <label class="block mb-1 font-medium">Username</label>
            <input type="text" x-model="editForm.username" class="w-full border px-3 py-2 rounded">
            <p class="text-red-500 text-sm mt-1" x-show="errors.username" x-text="errors.username"></p>
        </div>

        <!-- Password -->
        <div>
            <label class="block mb-1 font-medium">Password</label>
            <input type="text" x-model="editForm.password" class="w-full border px-3 py-2 rounded">
            <p class="text-red-500 text-sm mt-1" x-show="errors.password" x-text="errors.password"></p>
        </div>

        <!-- Email -->
        <div>
            <label class="block mb-1 font-medium">Email</label>
            <input type="email" x-model="editForm.email" class="w-full border px-3 py-2 rounded">
            <p class="text-red-500 text-sm mt-1" x-show="errors.email" x-text="errors.email"></p>
        </div>

        <!-- Role -->
        <div>
            <label class="block mb-1 font-medium">Role</label>
            <select x-model="editForm.role" class="w-full border px-3 py-2 rounded">
                <option value="">-- Pilih Role --</option>
                <option value="admin">admin</option>
                <option value="user">user</option>
            </select>
            <p class="text-red-500 text-sm mt-1" x-show="errors.role" x-text="errors.role"></p>
        </div>

        <!-- Picture Upload -->
        <div>
            <label class="block mb-1 font-medium">Upload Foto (Opsional)</label>
            <input type="file" @change="editHandleFileUpload($event)" class="w-full border px-3 py-2 rounded">
            <p class="text-red-500 text-sm mt-1" x-show="errors.pic" x-text="errors.pic"></p>
        </div>

        <!-- ✅ Buttons inside modal box -->
        <div class="flex justify-end gap-2 mt-6">
            <button @click="editClose()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Tutup</button>
            <button @click="updateUser()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update</button>
        </div>

    </div>
</div>


<!-- <script>
// function userForm() {
//     return {
//         show: false, // for modalAdd
//         editShow: false, // for modalEdit

//         form: {
//             name: '',
//             username: '',
//             password: '',
//             email: '',
//             role: '',
//             pic: null
//         },

//         editForm: {
//             _id: '',
//             name: '',
//             username: '',
//             password: '',
//             email: '',
//             role: '',
//             pic: null
//         },

//         errors: {},

//         open() {
//             this.show = true;
//         },
//         close() {
//             this.show = false;
//             this.editShow = false;
//         },
//         editOpen(user) {
//             this.errors = {};
//             this.editForm = { ...user };
//             this.editShow = true;
//         },
//         editClose() {
//             this.editShow = false;
//         },
//         editHandleFileUpload(event) {
//             this.editForm.pic = event.target.files[0];
//         },

//         async updateUser() {
//             const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
//             const formData = new FormData();
//             formData.append('_method', 'PUT');
//             formData.append('name', this.editForm.name);
//             formData.append('username', this.editForm.username);
//             formData.append('password', this.editForm.password);
//             formData.append('email', this.editForm.email);
//             formData.append('role', this.editForm.role);
//             if (this.editForm.pic) {
//                 formData.append('pic', this.editForm.pic);
//             }

//             try {
//                 const response = await fetch(`/users/${this.editForm._id}`, {
//                     method: 'POST',
//                     headers: {
//                         'X-CSRF-TOKEN': token
//                     },
//                     body: formData
//                 });

//                 const result = await response.json();

//                 if (!response.ok) {
//                     this.errors = result.errors || {};
//                     return;
//                 }

//                 Swal.fire({
//                     icon: 'success',
//                     title: 'User berhasil diupdate!',
//                     showConfirmButton: false,
//                     timer: 3000
//                 });

//                 this.editClose();
//             } catch (err) {
//                 console.error(err);
//                 Swal.fire({
//                     icon: 'error',
//                     title: 'Terjadi kesalahan.',
//                     text: 'Gagal mengupdate data.',
//                 });
//             }
//         }
//     };
// }
// </script>
 -->

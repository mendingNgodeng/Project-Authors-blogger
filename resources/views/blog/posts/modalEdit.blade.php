<!-- Modal Edit -->
<div x-show="editShow" x-cloak
    @keydown.escape.window="close()" 
    x-transition 
class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
  <div @click.away="close()" class="bg-white rounded-lg p-6 w-full max-w-2xl shadow-lg">
    <h2 class="text-xl font-semibold mb-4">Edit Post</h2>
     <input type="hidden" x-model="editForm._id" class="w-full border px-3 py-2 rounded">
     <input type="hidden" x-model="editForm.role" class="w-full border px-3 py-2 rounded">
     <input type="hidden" x-model="editForm.poster" class="w-full border px-3 py-2 rounded">
     
    <!-- Form Fields -->
        <div class="space-y-4">
            <!-- Title -->
            <div>
                <label class="block mb-1 font-medium">Judul</label>
                <input type="text" x-model="editForm.title" class="w-full border px-3 py-2 rounded">
                <p class="text-red-500 text-sm mt-1" x-show="errors.title" x-text="errors.title"></p>
            </div>

            <!-- Content -->
            <div>
                <label class="block mb-1 font-medium">Deskripsi</label>
                <textarea x-model="editForm.content" rows="5" class="w-full border px-3 py-2 rounded"></textarea>
                <p class="text-red-500 text-sm mt-1" x-show="errors.content" x-text="errors.content"></p>
            </div>

            <!-- Media -->
            <div>
                <label class="block mb-1 font-medium">Upload Media</label>
                <input type="file" multiple @change="handleEditFileUpload($event)" class="w-full border px-3 py-2 rounded">
                <p class="text-red-500 text-sm mt-1" x-show="errors.media" x-text="errors.media"></p>
            </div>

            <!-- Category -->
            <div>
                <label class="block mb-1 font-medium">Kategori</label>
                <input type="text" x-model="editForm.category" class="w-full border px-3 py-2 rounded">
                <p class="text-red-500 text-sm mt-1" x-show="errors.category" x-text="errors.category"></p>
            </div>

            <!-- Tags -->
            <div>
                <label class="block mb-1 font-medium">Tags (pisahkan dengan koma)</label>
                <input type="text" x-model="editForm.tags" class="w-full border px-3 py-2 rounded">
                <p class="text-red-500 text-sm mt-1" x-show="errors.tags" x-text="errors.tags"></p>
            </div>

            <!-- Status -->
            <div>
                <label class="block mb-1 font-medium">Status</label>
                <select x-model="editForm.status" class="w-full border px-3 py-2 rounded">
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
                <p class="text-red-500 text-sm mt-1" x-show="errors.status" x-text="errors.status"></p>
            </div>
        </div>


    <div class="flex justify-end gap-2 mt-6">
      <button @click="close()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Tutup</button>
     <button @click="updatePost()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>

    </div>
  </div>
</div>

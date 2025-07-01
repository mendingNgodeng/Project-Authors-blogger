<!-- Detail Modal -->
<div 
  x-show="show" 
  x-transition 
  x-cloak 
  class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
  @click.self="close()"
>
  <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-xl max-h-[90vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold">Detail Komentar</h2>
      <button @click="close()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
    </div>

    <div>
      <p><strong>Username:</strong> <span x-text="comment.user?.username ?? 'Guest'"></span></p>
      <p><strong>Nama:</strong> <span x-text="comment.user?.name ?? '-'"></span></p>
      <p><strong>Role:</strong> <span x-text="comment.user?.role ?? '-'"></span></p>
      <p><strong>Post:</strong> <span x-text="comment.post?.title ?? '[Post Deleted]'"></span></p>
      <p><strong>Komentar:</strong></p>
      <p class="bg-gray-100 rounded p-3 mt-1" x-text="comment.comment"></p>
      <p class="mt-2 text-sm text-gray-500"><strong>Tanggal:</strong> <span x-text="comment.created_at"></span></p>
    </div>
  </div>
</div>

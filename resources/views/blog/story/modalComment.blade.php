<!-- Comment Modal -->
<div 
    x-data="{ open: false }" 
    x-on:open-comment-modal.window="open = true" 
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-transition 
    x-cloak
    class="fixed top-0 right-0 h-full w-full sm:w-[400px] bg-white shadow-lg z-50 overflow-y-auto border-l border-gray-300"
>
    <!-- Modal Header -->
    <div class="flex justify-between items-center px-4 py-3 border-b bg-gray-100">
        <h2 class="text-lg font-semibold">Comments</h2>
        <button @click="open = false" class="text-gray-600 text-xl hover:text-black">&times;</button>
    </div>

    <!-- Modal Body -->
    <div class="p-4">
        <!-- input -->
         <p class="mb-2 text-sm text-gray-500">Please Keep Your Mannerism</p>

    <livewire:comment-chapter :chapter_id="$chapter->_id" />
    </div>
</div>

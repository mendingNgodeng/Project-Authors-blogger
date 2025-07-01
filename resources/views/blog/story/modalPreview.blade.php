<div x-show="open" x-transition x-cloak @keydown.escape.window="open = false"
     class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full p-6 relative" @click.away="open = false">
        <button @click="open = false" class="absolute top-2 right-2 text-gray-600 hover:text-black text-2xl">&times;</button>

        <template x-if="selectedStory">
            <div class="flex gap-2">
                <!-- Left Column -->
                <div class="w-1/2 max-h-[50vh] overflow-y-auto">
                    <img 
                    :src="selectedStory.cover ? '{{ asset('/') }}/' + selectedStory.cover : ''"
                    alt="Cover"
                    class="w-full h-96 object-contain rounded"
                    />
                    <h2 class="text-xl font-bold mb-2" x-text="selectedStory.title"></h2>
                    <p class="text-sm text-gray-700 mb-2"><strong>Summary:</strong>
                        <span x-html="selectedStory.summary"></span>
                    </p>
                    <p class="text-sm text-gray-600"><strong>Published:</strong>
                        <span x-text="new Date(selectedStory.created_at).toLocaleString()"></span>
                    </p>
                    <p class="text-sm text-gray-600"><strong>Updated:</strong>
                        <span x-text="new Date(selectedStory.updated_at).toLocaleString()"></span>
                    </p>
                
                </div>

                <!-- Right Column -->
                <div class="w-1/2 overflow-y-auto max-h-[50vh]">
                    <h3 class="text-lg font-semibold mb-3">Chapters</h3>
                    <template x-if="selectedStory.chapters.length">
                        <ul class="space-y-3 ">
                            <template x-for="chapter in selectedStory.chapters" :key="chapter._id">
                                <li class="border rounded-lg p-3 hover:shadow-lg duration-300 transition-all">
                                    <h4 class="font-bold text-white bg-blue-500 p-2 rounded-lg cursor-pointer">
                                        <a :href="'/chapters/read/' + chapter._id" x-text="chapter.title" target="_blank"></a>
                                    </h4>
                                    <p class="text-sm text-gray-600">Published:
                                        <span x-text="new Date(chapter.created_at).toLocaleString()"></span>
                                    </p>
                                    <p class="text-sm text-gray-600">Updated:
                                        <span x-text="new Date(chapter.updated_at).toLocaleString()"></span>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        ❤️ Likes: <span x-text="chapter.likes_count"></span>
                                    </p>
                                    <p class="text-sm">Word Count:
                                        <span x-text="chapter.content ? chapter.content.split(/\s+/).length : 0"></span>
                                    </p>
                                </li>
                            </template>
                        </ul>
                    </template>
                    <p x-show="!selectedStory.chapters.length" class="text-gray-500">No chapters were made yet.</p>
                </div>
            </div>
        </template>
    </div>
</div>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight tracking-tight">
            {{ __('File Manager') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-black min-h-screen">
        <div class="max-w-[1800px] mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.300ms class="mb-8 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-green-800 dark:text-green-300 font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.300ms class="mb-8 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-red-800 dark:text-red-300 font-medium">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-800 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Images</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_images'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-800 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Directories</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['directories_count'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-800 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Size</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_size'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-800 p-6 mb-8">
                <form method="GET" action="{{ route('admin.files.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by filename or path..." 
                               class="w-full rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="w-full md:w-64">
                        <select name="directory" class="w-full rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all">All Directories</option>
                            @foreach($directories as $dir)
                                <option value="{{ $dir }}" {{ request('directory') === $dir ? 'selected' : '' }}>{{ $dir }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </button>
                </form>
            </div>

            @if($images->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 bg-white dark:bg-zinc-900 rounded-3xl border border-dashed border-gray-300 dark:border-zinc-700">
                    <div class="bg-indigo-50 dark:bg-indigo-900/30 p-4 rounded-full mb-4">
                        <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">No images found</h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400 text-sm">Try adjusting your search or filter criteria.</p>
                </div>
            @else
                <div id="images-gallery" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                    @foreach($images as $image)
                        <div class="group relative bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300">
                            <!-- Image -->
                            <div class="aspect-square w-full bg-gray-200 dark:bg-zinc-800 relative">
                                <img src="{{ $image['url'] }}" alt="{{ $image['name'] }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                
                                <!-- Overlay Actions -->
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center space-x-2">
                                    <a href="#" data-index="{{ $loop->index }}" class="view-full-btn p-2 bg-white/90 rounded-full hover:bg-white text-gray-800 transition-colors shadow-lg" title="View Full">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.files.download', base64_encode($image['path'])) }}" class="p-2 bg-blue-500/90 rounded-full hover:bg-blue-600 text-white transition-colors shadow-lg" title="Download">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>
                                    <button type="button" onclick="deleteImage('{{ $image['path'] }}')" class="p-2 bg-red-500/90 rounded-full hover:bg-red-600 text-white transition-colors shadow-lg" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Footer info -->
                            <div class="p-2 border-t border-gray-100 dark:border-zinc-800">
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate" title="{{ $image['path'] }}">{{ $image['directory'] }}/</p>
                                <p class="text-xs text-gray-700 dark:text-gray-300 font-medium truncate" title="{{ $image['name'] }}">{{ $image['name'] }}</p>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-[10px] text-gray-400">{{ $image['size_formatted'] }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $image['modified_formatted'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="delete-form" method="POST" action="{{ route('admin.files.delete') }}" style="display: none;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="paths[]" id="delete-path">
    </form>

    <!-- Viewer.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gallery = document.getElementById('images-gallery');
            if (gallery) {
                const viewer = new Viewer(gallery, {
                    toolbar: {
                        zoomIn: 1,
                        zoomOut: 1,
                        oneToOne: 1,
                        reset: 1,
                        prev: 1,
                        next: 1,
                        rotateLeft: 1,
                        rotateRight: 1,
                        flipHorizontal: 1,
                        flipVertical: 1,
                    },
                    title: false,
                    transition: false,
                });

                // Bind custom view full buttons
                const buttons = document.querySelectorAll('.view-full-btn');
                buttons.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const index = parseInt(btn.getAttribute('data-index'));
                        viewer.view(index);
                    });
                });
            }
        });

        function deleteImage(path) {
            if (confirm('Are you sure you want to delete this image? This action cannot be undone.')) {
                document.getElementById('delete-path').value = path;
                document.getElementById('delete-form').submit();
            }
        }
    </script>
</x-app-layout>

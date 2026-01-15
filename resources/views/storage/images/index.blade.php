<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight tracking-tight">
            {{ __('Image Library') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-black min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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

            @if($images->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 bg-white dark:bg-zinc-900 rounded-3xl border border-dashed border-gray-300 dark:border-zinc-700">
                    <div class="bg-indigo-50 dark:bg-indigo-900/30 p-4 rounded-full mb-4">
                        <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">No images in library</h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400 text-sm">Images from your prompts will appear here automatically.</p>
                </div>
            @else
                <div id="images-gallery" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($images as $image)
                        <div class="group relative bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 cursor-pointer">
                            <!-- Image -->
                            <div class="aspect-w-1 aspect-h-1 w-full bg-gray-200 dark:bg-zinc-800 relative">
                                <img src="{{ asset('storage/' . $image->path) }}" alt="Image" class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-500">
                                
                                <!-- Overlay Actions -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center space-x-3">
                                    <a href="#" data-index="{{ $loop->index }}" class="view-full-btn p-2 bg-white/90 rounded-full hover:bg-white text-gray-800 transition-colors shadow-lg" title="View Full">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    @if(auth()->id() === $image->user_id || auth()->user()->hasRole('Admin'))
                                        <form action="{{ route('storage.images.destroy', $image) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-500/90 rounded-full hover:bg-red-600 text-white transition-colors shadow-lg" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Bare Footer info -->
                            <div class="p-3 border-t border-gray-100 dark:border-zinc-800">
                                <div class="flex justify-between items-center mb-1">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $image->created_at->diffForHumans() }}</p>
                                    @if($image->user)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300" title="Creator">
                                            {{ $image->user->name }}
                                        </span>
                                    @endif
                                </div>
                                @if($image->tags)
                                    <p class="text-xs text-indigo-500 mt-1 truncate">
                                        {{ is_array($image->tags) ? implode(', ', $image->tags) : $image->tags }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $images->links() }}
                </div>
            @endif
        </div>
    </div>

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
                    transition: false, // Cleaner feel
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
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight">
                {{ $prompt->name }}
            </h2>
            <div class="flex space-x-3">
                @if(auth()->id() === $prompt->user_id || auth()->user()->hasRole('Admin'))
                    <a href="{{ route('storage.prompts.edit', $prompt) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Edit
                    </a>
                @endif
                <a href="{{ route('storage.prompts.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-black min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden">
                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Left Column: Image -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-100 dark:bg-zinc-800 rounded-xl overflow-hidden aspect-[3/4] relative border border-gray-200 dark:border-zinc-700">
                                @if($prompt->image_path)
                                    <img src="{{ Storage::url($prompt->image_path) }}" alt="{{ $prompt->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-sm font-medium">No Reference Image</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right Column: Details -->
                        <div class="lg:col-span-2">
                            <!-- Meta & Actions -->
                            <div class="flex justify-between items-start mb-6">
                                <!-- Meta Info -->
                                <div class="flex items-center space-x-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100/50 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200">
                                        {{ $prompt->category ?? 'General' }}
                                    </span>
                                    <span class="text-gray-400 text-sm">|</span>
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">Last updated {{ $prompt->updated_at->diffForHumans() }}</span>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2">
                                    <!-- Favorite Toggle -->
                                    <form action="{{ route('storage.prompts.toggle-favorite', $prompt) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg transition-colors {{ $prompt->is_favorite ? 'text-yellow-400 bg-yellow-50 dark:bg-yellow-900/20' : 'text-gray-400 hover:text-yellow-400 hover:bg-gray-100 dark:hover:bg-zinc-800' }}" title="Toggle Favorite">
                                            @if($prompt->is_favorite)
                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                            @else
                                                <svg class="w-5 h-5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                                            @endif
                                        </button>
                                    </form>

                                    <!-- Duplicate -->
                                    <form action="{{ route('storage.prompts.duplicate', $prompt) }}" method="POST" onsubmit="return confirm('Duplicate this prompt?');">
                                        @csrf
                                        <button type="submit" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors" title="Duplicate">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        </button>
                                    </form>

                                    <!-- Edit & Delete (Owner/Admin) -->
                                    @if(auth()->id() === $prompt->user_id || auth()->user()->hasRole('Admin'))
                                        <!-- Edit -->
                                        <a href="{{ route('storage.prompts.edit', $prompt) }}" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('storage.prompts.destroy', $prompt) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this prompt?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Prompt Box -->
                            <div class="bg-gray-50 dark:bg-black/50 border border-gray-200 dark:border-zinc-800 rounded-xl p-6 relative group">
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Full Prompt</h3>
                                <p class="font-mono text-base leading-relaxed text-slate-800 dark:text-slate-200 whitespace-pre-wrap">{{ $prompt->prompt }}</p>
                                
                                <!-- Copy Button -->
                                <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="navigator.clipboard.writeText(`{{ addslashes($prompt->prompt) }}`); alert('Copied to clipboard!');" 
                                        class="p-2 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg shadow-sm text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

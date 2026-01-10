<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight">
                {{ $prompt->name }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('storage.prompts.edit', $prompt) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    Edit
                </a>
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
                    
                    <!-- Meta info -->
                    <div class="flex items-center space-x-4 mb-8">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100/50 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200">
                            {{ $prompt->category ?? 'General' }}
                        </span>
                        @if($prompt->is_favorite)
                            <span class="inline-flex items-center text-yellow-500 font-medium text-sm">
                                <svg class="w-4 h-4 mr-1 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                Favorite
                            </span>
                        @endif
                        <span class="text-gray-400 text-sm">|</span>
                        <span class="text-gray-500 dark:text-gray-400 text-sm">Last updated {{ $prompt->updated_at->diffForHumans() }}</span>
                    </div>

                    <!-- Prompt Box -->
                    <div class="bg-gray-50 dark:bg-black/50 border border-gray-200 dark:border-zinc-800 rounded-xl p-6 mb-8 relative group">
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
</x-app-layout>

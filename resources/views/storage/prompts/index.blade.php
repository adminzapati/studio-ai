<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight tracking-tight">
                {{ __('My Prompts') }}
            </h2>
            
            <div class="flex flex-col md:flex-row w-full md:w-auto items-center gap-4">
                <!-- Search & Filters -->
                <form action="{{ route('storage.prompts.index') }}" method="GET" class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                    <!-- Search -->
                    <div class="relative w-full md:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search prompts..." 
                            class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm transition-colors">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Favorites Filter -->
                    <select name="favorites" onchange="this.form.submit()" class="w-full md:w-32 py-2 px-3 rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm cursor-pointer">
                        <option value="0">All Prompts</option>
                        <option value="1" {{ request('favorites') == '1' ? 'selected' : '' }}>Favorites</option>
                    </select>

                    <!-- Category Filter -->
                    <select name="category" onchange="this.form.submit()" class="w-full md:w-32 py-2 px-3 rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm cursor-pointer">
                        <option value="all">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>

                    <!-- Method Filter -->
                    <select name="method" onchange="this.form.submit()" class="w-full md:w-32 py-2 px-3 rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm cursor-pointer">
                        <option value="all">All Methods</option>
                        <option value="manual" {{ request('method') == 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="image" {{ request('method') == 'image' ? 'selected' : '' }}>From Image</option>
                        <option value="wizard" {{ request('method') == 'wizard' ? 'selected' : '' }}>Wizard</option>
                    </select>

                    <!-- Sort -->
                    <select name="sort" onchange="this.form.submit()" class="w-full md:w-32 py-2 px-3 rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm cursor-pointer">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>A-Z</option>
                        <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Z-A</option>
                    </select>
                </form>

                <a href="{{ route('storage.prompts.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 ease-in-out transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 whitespace-nowrap">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('Create Prompt') }}
                </a>
            </div>
        </div>
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

            @if($prompts->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 bg-white dark:bg-zinc-900 rounded-3xl border border-dashed border-gray-300 dark:border-zinc-700">
                    <div class="bg-indigo-50 dark:bg-indigo-900/30 p-4 rounded-full mb-4">
                        <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">No prompts found</h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400 text-sm">Get started by creating your first AI prompt.</p>
                    <div class="mt-6">
                        <a href="{{ route('storage.prompts.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create Prompt
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($prompts as $prompt)
                        <div x-data="{ copied: false }" class="group relative bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer flex flex-col h-full">
                            <!-- Image / Thumbnail -->
                            <div class="w-full aspect-[3/2] rounded-xl overflow-hidden mb-4 bg-gray-100 dark:bg-zinc-800 relative group-hover:ring-2 ring-indigo-500/20 transition-all">
                                @if($prompt->image_path)
                                    <img src="{{ Storage::url($prompt->image_path) }}" alt="{{ $prompt->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-zinc-600">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Favorite Badge -->
                                <!-- Favorite Badge -->
                                    <div class="absolute top-2 right-2 z-10">
                                        <form action="{{ route('storage.prompts.toggle-favorite', $prompt) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-white/90 dark:bg-black/90 p-1.5 rounded-full shadow-sm backdrop-blur-sm transition-transform hover:scale-110 focus:outline-none" title="Toggle Favorite">
                                                @if($prompt->is_favorite)
                                                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 fill-none stroke-current hover:text-yellow-400 transition-colors" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                            </div>

                            <!-- Header -->
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-1">
                                        {{ $prompt->name }}
                                    </h3>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $prompt->category ?? 'General' }}
                                    </span>
                                    @php
                                        $methodColors = [
                                            'manual' => 'bg-gray-100 text-gray-600 dark:bg-zinc-700 dark:text-gray-400',
                                            'image' => 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-300',
                                            'wizard' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300',
                                        ];
                                        $methodLabel = [
                                            'manual' => 'Manual',
                                            'image' => 'Image',
                                            'wizard' => 'Wizard',
                                        ][$prompt->method ?? 'manual'] ?? 'Manual';
                                        $methodClass = $methodColors[$prompt->method ?? 'manual'] ?? $methodColors['manual'];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $methodClass }} mt-1 ml-1">
                                        {{ $methodLabel }}
                                    </span>
                                    @if($prompt->user)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 mt-1 ml-1 min-w-[60px] justify-center" title="Creator">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            {{ $prompt->user->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-grow">
                                <div class="bg-gray-50 dark:bg-black/50 border border-gray-100 dark:border-zinc-800 rounded-xl p-3 mb-4 h-20 overflow-hidden relative">
                                    <p class="text-xs text-gray-600 dark:text-gray-300 font-mono leading-relaxed pr-6" style="word-break: break-word;">
                                        {{ $prompt->prompt }}
                                    </p>
                                    <div class="absolute bottom-0 left-0 w-full h-8 bg-gradient-to-t from-gray-50 dark:from-black/50 to-transparent"></div>
                                    
                                    <!-- Copy Button -->
                                    <button @click.prevent="navigator.clipboard.writeText({{ json_encode($prompt->prompt) }}); copied = true; window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Prompt copied to clipboard!', type: 'success' } })); setTimeout(() => copied = false, 2000)" 
                                        class="absolute top-2 right-2 p-1 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 bg-white/80 dark:bg-black/80 rounded-lg hover:bg-white dark:hover:bg-black transition-colors shadow-sm z-10"
                                        title="Copy Prompt">
                                        <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <svg x-show="copied" class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Footer/Actions -->
                            <div class="pt-3 border-t border-gray-100 dark:border-zinc-800 flex justify-between items-center relative z-20 pointer-events-none">
                                <span class="text-xs text-gray-400 dark:text-gray-500 font-medium">
                                    {{ $prompt->created_at->diffForHumans() }}
                                </span>
                                <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-auto">
                                    
                                    <!-- Duplicate Button -->
                                    <form action="{{ route('storage.prompts.duplicate', $prompt) }}" method="POST" class="inline-block" onsubmit="return confirm('Do you want to duplicate this prompt?');">
                                        @csrf
                                        <button type="submit" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors" title="Duplicate">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </button>
                                    </form>

                                    <!-- Edit & Delete Buttons (Owner or Admin) -->
                                    @if(auth()->id() === $prompt->user_id || auth()->user()->hasRole('Admin'))
                                        <!-- Edit Button -->
                                        <a href="{{ route('storage.prompts.edit', $prompt) }}" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('storage.prompts.destroy', $prompt) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this prompt?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-full hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Detailed Link Overlay -->
                            <a href="{{ route('storage.prompts.show', $prompt) }}" class="absolute inset-0 z-0"></a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $prompts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

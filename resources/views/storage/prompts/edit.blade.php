<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight">
            {{ __('Edit Prompt') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-black min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-12">
                <!-- Sidebar info -->
                <div class="lg:col-span-4 mb-6 lg:mb-0">
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-gray-200 dark:border-zinc-800">
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-4">Prompt Details</h3>
                        <div class="space-y-4">
                            <div>
                                <span class="block text-xs text-gray-400 uppercase tracking-wider font-semibold">Created</span>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $prompt->created_at->format('F j, Y, g:i a') }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-400 uppercase tracking-wider font-semibold">Last Modified</span>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $prompt->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Form -->
                <div class="lg:col-span-8">
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden">
                        <form action="{{ route('storage.prompts.update', $prompt) }}" method="POST" class="p-8">
                            @csrf
                            @method('PUT')

                            <!-- Name & Category Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prompt Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $prompt->name) }}" required autofocus 
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm py-2.5">
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                                    <select id="category" name="category" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm py-2.5">
                                        <option value="">Select a Category...</option>
                                        @foreach(['Fashion', 'E-Commerce', 'Portrait', 'Product', 'Other'] as $cat)
                                            <option value="{{ $cat }}" {{ (old('category', $prompt->category) == $cat) ? 'selected' : '' }}>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Prompt Content -->
                            <div class="mb-6">
                                <label for="prompt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prompt Content</label>
                                <div class="relative">
                                    <textarea id="prompt" name="prompt" rows="8" required 
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm leading-relaxed p-4 font-mono">{{ old('prompt', $prompt->prompt) }}</textarea>
                                </div>
                                <x-input-error :messages="$errors->get('prompt')" class="mt-2" />
                            </div>

                            <hr class="border-gray-100 dark:border-gray-800 mb-6">

                            <!-- Footer Actions -->
                            <div class="flex items-center justify-between">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_favorite" value="1" class="rounded dark:bg-zinc-800 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer" {{ old('is_favorite', $prompt->is_favorite) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 font-medium">Add to Favorites</span>
                                </label>

                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('storage.prompts.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                        Cancel
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        {{ __('Update Prompt') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

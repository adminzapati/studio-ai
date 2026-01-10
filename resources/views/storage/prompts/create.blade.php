<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight">
            {{ __('Create New Prompt') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-black min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-12">
                <!-- Sidebar help -->
                <div class="lg:col-span-4 mb-6 lg:mb-0">
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl p-6 border border-indigo-100 dark:border-indigo-800">
                        <div class="flex items-center mb-4">
                            <div class="bg-indigo-600 rounded-lg p-2 mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="font-bold text-indigo-900 dark:text-indigo-100 text-lg">Pro Tips</h3>
                        </div>
                        <ul class="space-y-4 text-sm text-indigo-800 dark:text-indigo-200">
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Be specific about lighting (e.g., "soft studio lighting", "golden hour").</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Describe the camera angle (e.g., "low angle", "eye level").</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Mention the background clearly (e.g., "clean white background", "urban street blurred").</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Main Form -->
                <div class="lg:col-span-8">
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden">
                        <form action="{{ route('storage.prompts.store') }}" method="POST" class="p-8">
                            @csrf

                            <!-- Name & Category Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prompt Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus placeholder="e.g., Summer Collection Outdoor" 
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm py-2.5">
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                                    <select id="category" name="category" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm py-2.5">
                                        <option value="">Select a Category...</option>
                                        <option value="Fashion">Fashion</option>
                                        <option value="E-Commerce">E-Commerce</option>
                                        <option value="Portrait">Portrait</option>
                                        <option value="Product">Product</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Prompt Content -->
                            <div class="mb-6">
                                <label for="prompt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prompt Content</label>
                                <div class="relative">
                                    <textarea id="prompt" name="prompt" rows="8" required placeholder="Describe your image generation prompt here..."
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm leading-relaxed p-4 font-mono">{{ old('prompt') }}</textarea>
                                    <div class="absolute bottom-3 right-3 text-xs text-gray-400 pointer-events-none">Markdown supported</div>
                                </div>
                                <x-input-error :messages="$errors->get('prompt')" class="mt-2" />
                            </div>

                            <hr class="border-gray-100 dark:border-gray-800 mb-6">

                            <!-- Footer Actions -->
                            <div class="flex items-center justify-between">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_favorite" value="1" class="rounded dark:bg-zinc-800 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer" {{ old('is_favorite') ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 font-medium">Add to Favorites</span>
                                </label>

                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('storage.prompts.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                        Cancel
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        {{ __('Save Prompt') }}
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

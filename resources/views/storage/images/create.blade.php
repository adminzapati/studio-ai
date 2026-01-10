<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight">
            {{ __('Upload Image') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-black min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-12">
                <!-- Sidebar info -->
                <div class="lg:col-span-4 mb-6 lg:mb-0">
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl p-6 border border-indigo-100 dark:border-indigo-800">
                        <div class="flex items-center mb-4">
                            <div class="bg-indigo-600 rounded-lg p-2 mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="font-bold text-indigo-900 dark:text-indigo-100 text-lg">Upload Tips</h3>
                        </div>
                        <ul class="space-y-4 text-sm text-indigo-800 dark:text-indigo-200">
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Supported formats: JPEG, PNG, WEBP, GIF.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Maximum file size: 10MB.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Images will be stored publicly in your library.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Main Form -->
                <div class="lg:col-span-8">
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden">
                        <form action="{{ route('storage.images.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                            @csrf

                            <!-- File Upload Area -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Image File</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-zinc-700 border-dashed rounded-xl hover:border-indigo-500 dark:hover:border-indigo-500 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                            <label for="image" class="relative cursor-pointer bg-white dark:bg-zinc-900 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload a file</span>
                                                <input id="image" name="image" type="file" class="sr-only" required accept="image/*">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">
                                            PNG, JPG, GIF up to 10MB
                                        </p>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>

                            <!-- Tags -->
                            <div class="mb-6">
                                <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tags (Optional)</label>
                                <input type="text" name="tags" id="tags" value="{{ old('tags') }}" placeholder="fashion, summer, blue" 
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm py-2.5">
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Comma separated keywords to help you search later.</p>
                                <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                            </div>

                            <hr class="border-gray-100 dark:border-gray-800 mb-6">

                            <!-- Footer Actions -->
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('storage.images.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Upload') }}
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

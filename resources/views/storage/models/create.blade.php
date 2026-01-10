<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight">
            {{ __('Add New Model') }}
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
                            <h3 class="font-bold text-indigo-900 dark:text-indigo-100 text-lg">System Models</h3>
                        </div>
                        <p class="text-sm text-indigo-800 dark:text-indigo-200 mb-4">
                            These models will be available for all users to use in the Virtual Model Studio.
                        </p>
                        <ul class="space-y-4 text-sm text-indigo-800 dark:text-indigo-200">
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Images should be high resolution and front-facing.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Ensure neutral lighting and clean background.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Main Form -->
                <div class="lg:col-span-8">
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden">
                        <form action="{{ route('storage.models.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                            @csrf

                            <!-- Name -->
                            <div class="mb-6">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Model Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm py-2.5">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Attributes Row -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gender</label>
                                    <select id="gender" name="gender" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm py-2.5">
                                        <option value="Female">Female</option>
                                        <option value="Male">Male</option>
                                        <option value="Unisex">Unisex</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                                </div>
                                <div>
                                    <label for="ethnicity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ethnicity</label>
                                    <input type="text" name="ethnicity" id="ethnicity" value="{{ old('ethnicity') }}" required placeholder="e.g., Asian, Caucasian"
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm py-2.5">
                                    <x-input-error :messages="$errors->get('ethnicity')" class="mt-2" />
                                </div>
                                <div>
                                    <label for="age_range" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Age Range</label>
                                    <input type="text" name="age_range" id="age_range" value="{{ old('age_range') }}" placeholder="e.g., 20-25"
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm py-2.5">
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Model Image</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-zinc-700 border-dashed rounded-xl hover:border-indigo-500 transition-colors">
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
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>

                            <hr class="border-gray-100 dark:border-gray-800 mb-6">

                            <!-- Footer Actions -->
                            <div class="flex items-center justify-between">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" class="rounded dark:bg-zinc-800 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer" checked>
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 font-medium">Active</span>
                                </label>

                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('storage.models.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                        Cancel
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        {{ __('Create Model') }}
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

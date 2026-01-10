<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">{{ __('Add Model Preset') }}</h2>
    </x-slot>
    <div class="max-w-2xl mx-auto">
        <form method="POST" action="{{ route('admin.model-presets.store') }}" enctype="multipart/form-data" class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-8 space-y-6">
            @csrf
            <div>
                <x-input-label for="gender" :value="__('Gender')" />
                <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-zinc-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="female">Female</option>
                    <option value="male">Male</option>
                    <option value="unisex">Unisex</option>
                </select>
            </div>
            <div>
                <x-input-label for="ethnicity" :value="__('Ethnicity/Description')" />
                <x-text-input id="ethnicity" class="block mt-1 w-full" type="text" name="ethnicity" :value="old('ethnicity')" required placeholder="e.g. Asian, Caucasian, Mixed" />
            </div>
            <div>
                <x-input-label for="image" :value="__('Model Image')" />
                <input type="file" id="image" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required />
            </div>
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.model-presets.index') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">Cancel</a>
                <x-primary-button>{{ __('Create Preset') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>

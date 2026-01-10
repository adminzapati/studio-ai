<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">{{ __('Add Product') }}</h2>
    </x-slot>
    <div class="max-w-2xl mx-auto">
        <form method="POST" action="{{ route('storage.products.store') }}" class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-8 space-y-6">
            @csrf
            <div>
                <x-input-label for="name" :value="__('Product Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="category" :value="__('Category')" />
                <x-text-input id="category" class="block mt-1 w-full" type="text" name="category" :value="old('category')" required />
                <x-input-error :messages="$errors->get('category')" class="mt-2" />
            </div>
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('storage.products.index') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">Cancel</a>
                <x-primary-button>{{ __('Create Product') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>

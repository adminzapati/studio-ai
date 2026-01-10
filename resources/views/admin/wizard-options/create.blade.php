<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">{{ __('Add Wizard Option') }}</h2>
    </x-slot>
    <div class="max-w-2xl mx-auto">
        <form method="POST" action="{{ route('admin.wizard-options.store') }}" class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-8 space-y-6">
            @csrf
            <div>
                <x-input-label for="step" :value="__('Step (1-5)')" />
                <x-text-input id="step" class="block mt-1 w-full" type="number" name="step" min="1" max="5" :value="old('step')" required />
            </div>
            <div>
                <x-input-label for="category" :value="__('Category')" />
                <x-text-input id="category" class="block mt-1 w-full" type="text" name="category" :value="old('category')" required placeholder="e.g. Style, Floor, Lighting" />
            </div>
            <div>
                <x-input-label for="value" :value="__('Value')" />
                <x-text-input id="value" class="block mt-1 w-full" type="text" name="value" :value="old('value')" required placeholder="e.g. Flat Lay, Wood, Soft" />
            </div>
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.wizard-options.index') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">Cancel</a>
                <x-primary-button>{{ __('Create Option') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>

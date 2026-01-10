<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">{{ __('System Settings') }}</h2>
    </x-slot>
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-8">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">API Configuration</h3>
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            <div>
                <x-input-label for="gemini_api_key" :value="__('Google Gemini API Key')" />
                <x-text-input id="gemini_api_key" class="block mt-1 w-full" type="password" name="gemini_api_key" placeholder="AIza..." />
            </div>
            <div>
                <x-input-label for="fal_api_key" :value="__('Fal.ai API Key')" />
                <x-text-input id="fal_api_key" class="block mt-1 w-full" type="password" name="fal_api_key" placeholder="fal_..." />
            </div>
            <x-primary-button>{{ __('Save Settings') }}</x-primary-button>
        </form>
    </div>
</x-app-layout>

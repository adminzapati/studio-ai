<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
            {{ __('Edit Plan: ') }} {{ $subscriptionPlan->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.subscription-plans.update', $subscriptionPlan) }}" class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Plan Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $subscriptionPlan->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Price -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="price" :value="__('Price ($/month)')" />
                        <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" :value="old('price', $subscriptionPlan->price)" required />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="credits_monthly" :value="__('Credits / Month')" />
                        <x-text-input id="credits_monthly" class="block mt-1 w-full" type="number" name="credits_monthly" :value="old('credits_monthly', $subscriptionPlan->credits_monthly)" required />
                        <x-input-error :messages="$errors->get('credits_monthly')" class="mt-2" />
                    </div>
                </div>

                <!-- Active Status -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $subscriptionPlan->is_active) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</label>
                </div>

                <!-- Module Selection -->
                <div class="border-t border-gray-100 dark:border-zinc-800 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Module Access</h3>
                    <p class="text-sm text-gray-500 mb-4">Select which modules are included in this plan.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($modules as $module)
                            <label class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <input type="checkbox" name="modules[]" value="{{ $module->slug }}" 
                                    {{ in_array($module->slug, $planModules) ? 'checked' : '' }}
                                    class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">{{ $module->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Features (Text/JSON) -->
                <div class="border-t border-gray-100 dark:border-zinc-800 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Features (displayed on pricing page)</h3>
                    @php $features = $subscriptionPlan->features ?? []; @endphp
                    <div id="features-container" class="space-y-2">
                        @forelse($features as $index => $feature)
                            <div class="flex items-center gap-2">
                                <input type="text" name="features[]" value="{{ $feature }}" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        @empty
                            <div class="flex items-center gap-2">
                                <input type="text" name="features[]" placeholder="Feature description..." class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        @endforelse
                    </div>
                    <button type="button" onclick="addFeatureInput()" class="mt-3 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">+ Add Feature</button>
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-gray-100 dark:border-zinc-800 gap-4">
                    <a href="{{ route('admin.subscription-plans.index') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">Cancel</a>
                    <x-primary-button>
                        {{ __('Update Plan') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addFeatureInput() {
            const container = document.getElementById('features-container');
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2';
            div.innerHTML = `
                <input type="text" name="features[]" placeholder="Feature description..." class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;
            container.appendChild(div);
        }
    </script>
</x-app-layout>

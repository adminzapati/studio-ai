<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
            {{ __('Edit User: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Role -->
                <div>
                    <x-input-label for="role" :value="__('Role')" />
                    <select id="role" name="role" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-zinc-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ (old('role') ?? $user->roles->first()->name ?? '') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <!-- Status -->
                <div class="flex items-center pt-8">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $user->is_active ? 'checked' : '' }}>
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Active Account</span>
                    </label>
                </div>
            </div>

            <!-- Subscription & Credits -->
            <div class="border-t border-gray-100 dark:border-zinc-800 pt-6 mt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Subscription & Credits</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="manual_credits" :value="__('Manual Credits (Current Balance)')" />
                        <x-text-input id="manual_credits" class="block mt-1 w-full" type="number" name="manual_credits" 
                            :value="old('manual_credits', $user->activeSubscription->credits_remaining ?? 0)" min="0" />
                        <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">
                            Set the exact number of credits available to this user. 
                            Current Plan: {{ $user->activeSubscription?->plan?->name ?? 'None' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Module Access Overrides (Admin Only) -->
            <div class="border-t border-gray-100 dark:border-zinc-800 pt-6 mt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Module Access Overrides</h3>
                <p class="text-sm text-gray-500 mb-4">Override global/subscription settings for this user.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($modules as $module)
                        @php
                            $overrideValue = isset($userOverrides[$module->id]) ? ($userOverrides[$module->id] ? '1' : '0') : 'default';
                        @endphp
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="p-2 bg-white dark:bg-gray-700 rounded-lg">
                                    <!-- Icon placeholder for now, or use module icon -->
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $module->name }}</span>
                            </div>
                            <select name="module_overrides[{{ $module->id }}]" class="block w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="default" {{ $overrideValue === 'default' ? 'selected' : '' }}>Use Plan Default</option>
                                <option value="1" {{ $overrideValue === '1' ? 'selected' : '' }}>Force Enable</option>
                                <option value="0" {{ $overrideValue === '0' ? 'selected' : '' }}>Force Disable</option>
                            </select>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-gray-100 dark:border-zinc-800 my-6"></div>
            
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Change Password <span class="text-sm font-normal text-gray-500">(Leave blank to keep current)</span></h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('New Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-gray-100 dark:border-zinc-800 gap-4">
                <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">Cancel</a>
                <x-primary-button>
                    {{ __('Update User') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>

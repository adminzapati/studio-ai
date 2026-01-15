<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">{{ __('System Settings') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-green-800 dark:text-green-300 font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-600 dark:text-green-400 hover:text-green-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-zinc-800">
                    <div class="flex items-center">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">API Configuration</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Manage your AI service credentials</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.settings.update') }}" class="p-6 space-y-6">
                    @csrf
                    
                    <!-- Gemini API Key -->
                    <div>
                        <label for="gemini_api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Google Gemini API Key
                        </label>
                        <div class="relative">
                            <input type="password" id="gemini_api_key" name="gemini_api_key" 
                                   value="{{ $settings['gemini_api_key'] }}"
                                   placeholder="AIza..."
                                   class="w-full rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm pr-10">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Used for image analysis and prompt generation</p>
                    </div>

                    <!-- Fal.ai API Key -->
                    <div>
                        <label for="fal_api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Fal.ai API Key
                        </label>
                        <div class="relative">
                            <input type="password" id="fal_api_key" name="fal_api_key"
                                   value="{{ $settings['fal_api_key'] }}"
                                   placeholder="fal_..."
                                   class="w-full rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm pr-10">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </div>
                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Used for image generation features</p>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 dark:border-zinc-700"></div>

                    <!-- Products Virtual Settings -->
                    <div class="pt-4">
                        <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                            Products Virtual Settings
                        </h4>
                        
                        <!-- Dev Mode Toggle -->
                        <div class="flex items-center justify-between p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800 mb-4">
                            <div>
                                <label for="products_virtual_dev_mode" class="text-sm font-medium text-gray-800 dark:text-gray-200">Dev Mode</label>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Bypass Fal.ai API, show debug JSON in UI modal</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="products_virtual_dev_mode" name="products_virtual_dev_mode" value="1" 
                                       {{ $settings['products_virtual_dev_mode'] ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 dark:peer-focus:ring-amber-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-amber-500"></div>
                            </label>
                        </div>

                        <!-- Quota Settings -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="products_virtual_daily_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Daily Limit
                                </label>
                                <input type="number" id="products_virtual_daily_limit" name="products_virtual_daily_limit" 
                                       value="{{ $settings['products_virtual_daily_limit'] }}"
                                       min="1" placeholder="10"
                                       class="w-full rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Per user per day</p>
                            </div>
                            <div>
                                <label for="products_virtual_total_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Total Limit
                                </label>
                                <input type="number" id="products_virtual_total_limit" name="products_virtual_total_limit" 
                                       value="{{ $settings['products_virtual_total_limit'] }}"
                                       min="1" placeholder="100"
                                       class="w-full rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Lifetime per user</p>
                            </div>
                        </div>
                            </div>
                        </div>

                        <!-- Storage Management -->
                        <div class="mt-6 pt-4 border-t border-gray-100 dark:border-zinc-800">
                             <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Storage Management
                            </h4>
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-xl border border-gray-200 dark:border-zinc-700">
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Temporary Files (Uploaded & Generated)</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Location: <code class="bg-gray-200 dark:bg-zinc-700 px-1 rounded">storage/temp/products-virtual</code>
                                    </p>
                                    <div class="flex items-center gap-4 mt-2">
                                        <span class="text-xs font-semibold bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                                            Size: {{ $tempStats['size'] }}
                                        </span>
                                        <span class="text-xs font-semibold bg-gray-100 text-gray-700 px-2 py-1 rounded-full">
                                            Files: {{ $tempStats['count'] }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <button form="clear-temp-form" type="submit" 
                                            onclick="return confirm('Are you sure? This will delete all temporary images.')"
                                            class="px-4 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 text-red-600 dark:text-red-400 text-sm font-medium rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors shadow-sm">
                                        Clear Temp Files
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Close main form, create separate form for clear action to avoid conflict -->
                    </form>
                    
                    <form id="clear-temp-form" method="POST" action="{{ route('admin.settings.clear-temp') }}" class="hidden">
                        @csrf
                    </form>
                    
                    <!-- Re-open main form structure context (trick for valid HTML if needed, but better to close form correctly above) -->
                    <!-- Wait, the original code had one form wrapping everything. I closed it above. But the original code closed it at line 137. -->
                    <!-- I should NOT close it inside the content block. -->
                    <!-- I must put the Clear Button OUTSIDE the main form or use 'form=' attribute. -->
                    <!-- I used 'form="clear-temp-form"' on the button. And added the hidden form below the main form closing tag. -->
                    <!-- BUT my ReplacementContent ENDS with </div>, which matches line 127 closing the 'pt-4' div. -->
                    <!-- The main form closes at line 137. -->
                    <!-- SO I should NOT close the form in my replacement. -->
                    <!-- I will insert the Storage Management block BEFORE the submit button div (line 129). -->

                    <!-- Correct Strategy: Insert before line 129. -->

                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

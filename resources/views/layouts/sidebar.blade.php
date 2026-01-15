<aside class="bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 min-h-screen flex flex-col fixed left-0 top-0 bottom-0 z-50 transition-all duration-300"
       :class="sidebarOpen ? 'w-64' : 'w-20'">
    <!-- Logo -->
    <div class="h-16 flex items-center px-6 border-b border-gray-100 dark:border-gray-700 overflow-hidden whitespace-nowrap">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <x-application-logo class="block h-8 w-auto fill-current text-indigo-600 dark:text-indigo-400" />
            <span class="font-bold text-xl text-gray-900 dark:text-white tracking-tight" x-show="sidebarOpen" x-transition.opacity>Studio AI</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto overflow-x-hidden">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span class="ml-3" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-100 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Dashboard</span>
        </a>

        <!-- GROUP: FEATURES -->
        <div class="pt-4 pb-2" x-show="sidebarOpen">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Features</p>
        </div>
        <div class="pt-4 pb-2 text-center" x-show="!sidebarOpen" title="Features">
            <div class="h-0.5 w-8 bg-indigo-200 dark:bg-indigo-900 mx-auto rounded"></div>
        </div>

        <!-- Batch Processor -->
        <a href="{{ route('features.batch.index') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('features.batch.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="ml-3" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-100 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Batch Processor</span>
        </a>

        <!-- Beautifier -->
        <a href="{{ route('features.beautifier.index') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('features.beautifier.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            <span class="ml-3" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-100 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Beautifier</span>
        </a>

        <!-- Virtual Model -->
        <a href="{{ route('features.virtual-model.index') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('features.virtual-model.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span class="ml-3" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-100 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Virtual Model</span>
        </a>

        <!-- Products Virtual -->
        <a href="{{ route('features.products-virtual.index') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('features.products-virtual.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            <span class="ml-3" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-100 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Products Virtual</span>
        </a>



        <!-- GROUP: STORAGE -->
        <div class="pt-4 pb-2" x-show="sidebarOpen">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Storage</p>
        </div>
        <div class="pt-4 pb-2 text-center" x-show="!sidebarOpen" title="Storage">
            <div class="h-0.5 w-8 bg-emerald-200 dark:bg-emerald-900 mx-auto rounded"></div>
        </div>

        <!-- Prompts -->
        <a href="{{ route('storage.prompts.index') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('storage.prompts.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            <span class="ml-3" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-100 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Prompts</span>
        </a>

        <!-- Images Library -->
        <a href="{{ route('storage.images.index') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('storage.images.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="ml-3" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-100 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Images Library</span>
        </a>

        <!-- Model Presets -->
        <a href="{{ route('storage.model-presets.index') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('storage.model-presets.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <span class="ml-3" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-100 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Model Presets</span>
        </a>

        <!-- HISTORY -->
        <div class="pt-4" x-show="sidebarOpen"></div>
        <a href="{{ route('history.index') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('history.*') ? 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="ml-3" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-100 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">History</span>
        </a>

        <!-- GROUP: ADMIN (Admin Only) -->
        @role('Admin')
            <div class="pt-4 pb-2" x-show="sidebarOpen">
                <p class="px-4 text-xs font-semibold text-red-400 uppercase tracking-wider">Admin</p>
            </div>
            <div class="pt-4 pb-2 text-center" x-show="!sidebarOpen" title="Admin">
                <div class="h-0.5 w-8 bg-red-200 dark:bg-red-900 mx-auto rounded"></div>
            </div>

            <!-- Users -->
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('admin.users.*') ? 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="ml-3" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-100 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Users</span>
            </a>

            <!-- Settings -->
            <a href="{{ route('admin.settings.index') }}" 
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('admin.settings.*') ? 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="ml-3" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-100 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Settings</span>
            </a>

            <!-- Wizard Options -->
            <a href="{{ route('admin.wizard-options.index') }}" 
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('admin.wizard-options.*') ? 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                <span class="ml-3" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-100 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Wizard Options</span>
            </a>
        @endrole
    </nav>

    <!-- Bottom Actions -->
    <div class="p-4 border-t border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col gap-2">
        <a href="{{ route('profile.edit') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200 transition-colors whitespace-nowrap">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span class="ml-3" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-100 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Profile</span>
        </a>

        <!-- Toggle Button -->
        <button @click="toggleSidebar()" 
                class="flex items-center w-full px-3 py-2.5 text-sm font-medium rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors focus:outline-none"
                :class="!sidebarOpen ? 'justify-center' : ''">
            <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300" :class="sidebarOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
            <span class="ml-3 whitespace-nowrap" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-100 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Collapse</span>
        </button>
    </div>
</aside>

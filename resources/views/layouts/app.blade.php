<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" 
          x-data="{ 
              sidebarOpen: localStorage.getItem('sidebarOpen') === 'false' ? false : true,
              toggleSidebar() { 
                  this.sidebarOpen = !this.sidebarOpen; 
                  localStorage.setItem('sidebarOpen', this.sidebarOpen);
              } 
          }">
        <div class="min-h-screen bg-gray-50 dark:bg-black flex">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Wrapper -->
            <div class="flex-1 flex flex-col min-h-screen transition-all duration-300"
                 :class="sidebarOpen ? 'ml-64' : 'ml-20'">
                
                <!-- Top Header -->
                <header class="h-16 bg-white dark:bg-zinc-900 border-b border-gray-100 dark:border-zinc-800 flex items-center justify-between px-8 sticky top-0 z-40 backdrop-blur-md bg-opacity-90">
                    
                    <!-- Search Bar (Placeholder) -->
                    <div class="flex-1 max-w-lg flex items-center gap-4">
                        <div class="relative group flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-5 h-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" class="w-full py-2 pl-10 pr-4 bg-gray-50 dark:bg-zinc-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all placeholder-gray-400 dark:text-gray-200" placeholder="Search prompts, images, or settings...">
                        </div>
                    </div>

                    <!-- Right Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications (Placeholder) -->
                        <button class="relative p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-zinc-900"></span>
                        </button>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                                <img class="h-8 w-8 rounded-full object-cover border border-gray-200 dark:border-zinc-700" src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" alt="{{ Auth::user()->name }}" />
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Pro Plan</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-zinc-800 rounded-xl shadow-lg py-1 border border-gray-100 dark:border-zinc-700 z-50 origin-top-right">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700">Profile</a>
                                <div class="border-t border-gray-100 dark:border-zinc-700 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="{{ $attributes->get('padding', 'p-8') }} w-full overflow-x-hidden">
                    <div class="{{ $attributes->get('maxWidth', 'max-w-7xl') }} mx-auto">
                        @isset($header)
                            <div class="mb-8">
                                <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                                    {{ $header }}
                                </h2>
                                <!-- Breadcrumbs can go here -->
                            </div>
                        @endisset

                        <div class="animate-fade-in-up">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <!-- Global Toast Notification -->
        <div x-data="{ show: false, message: '', type: 'success' }" 
             @toast.window="show = true; message = $event.detail.message || $event.detail; type = $event.detail.type || 'success'; setTimeout(() => show = false, 3000)"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="fixed bottom-4 right-4 text-white px-6 py-3 rounded-xl shadow-lg flex items-center space-x-2 z-[9999]"
             :class="type === 'success' ? 'bg-green-500' : 'bg-red-500'"
             style="display: none;">
            <svg x-show="type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg x-show="type === 'error'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span x-text="message"></span>
        </div>

        @stack('scripts')
    </body>
</html>

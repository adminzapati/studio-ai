<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
            {{ __('Overview') }}
        </h2>
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stat Card 1 -->
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-zinc-800 p-6 transition-transform hover:-translate-y-1 duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Prompts</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->savedPrompts()->count() }}</h3>
                </div>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-zinc-800 p-6 transition-transform hover:-translate-y-1 duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Generated Images</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->images()->count() }}</h3>
                </div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-zinc-800 p-6 transition-transform hover:-translate-y-1 duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400">
                     <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Model Presets</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Active</h3>
                </div>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-gradient-to-br from-indigo-600 to-violet-600 overflow-hidden shadow-lg shadow-indigo-500/30 rounded-2xl p-6 text-white relative group cursor-pointer transition-transform hover:-translate-y-1 duration-300">
             <div class="absolute top-0 right-0 -mt-2 -mr-2 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
             <div class="relative z-10">
                <p class="text-indigo-100 font-medium mb-1">Pro Plan</p>
                <h3 class="text-2xl font-bold mb-4">Active</h3>
                <a href="#" class="text-xs font-bold bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg transition-colors inline-block">Manage Subscription</a>
             </div>
        </div>

        @role('Admin')
        <!-- Admin Stats: Total Users -->
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-zinc-800 p-6 transition-transform hover:-translate-y-1 duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::count() }}</h3>
                </div>
            </div>
        </div>
        @endrole
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Quick Actions (Bento Grid) -->
        <div class="lg:col-span-2 space-y-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Quick Actions</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- REMOVED Auto Prompt Wizard -->

                <!-- Action: Batch Processing -->
                <a href="{{ route('features.batch.index') }}" class="group relative bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between h-48 overflow-hidden grayscale hover:grayscale-0">
                     <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                         <svg class="w-24 h-24 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-xl flex items-center justify-center text-green-600 dark:text-green-400 mb-4 group-hover:scale-110 transition-transform">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-green-600 transition-colors">Batch Processor</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Generate multiple product images from a single prompt.</p>
                    </div>
                    <div class="mt-4 flex items-center text-green-600 font-medium text-sm group-hover:translate-x-1 transition-transform">
                        Launch Tool <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                </a>
            </div>
            
            <!-- Recent Prompts Section -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-zinc-800 flex justify-between items-center">
                    <h4 class="font-bold text-gray-900 dark:text-white">Recent Prompts</h4>
                    <a href="{{ route('storage.prompts.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">View All</a>
                </div>
                <!-- Placeholder List -->
                <ul class="divide-y divide-gray-50 dark:divide-zinc-800">
                    @forelse(Auth::user()->savedPrompts()->latest()->take(3)->get() as $prompt)
                    <li class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                        <div class="flex justify-between items-start">
                             <div>
                                 <h5 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $prompt->name }}</h5>
                                 <p class="text-xs text-gray-500 line-clamp-1 mt-1">{{ Str::limit($prompt->prompt, 60) }}</p>
                             </div>
                             <span class="text-xs text-gray-400">{{ $prompt->created_at->diffForHumans() }}</span>
                        </div>
                    </li>
                    @empty
                    <li class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                        No prompts yet. <a href="{{ route('storage.prompts.create') }}" class="text-indigo-600 font-semibold hover:underline">Create your first one!</a>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Right Column: System Status / Tips -->
        <div class="space-y-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">System & Insights</h3>

            <!-- System Status -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-6">
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">System Status</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                         <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-300">Gemini AI</span>
                         </div>
                         <span class="text-xs font-medium text-green-600 bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full">Operational</span>
                    </div>
                    <div class="flex items-center justify-between">
                         <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-300">Fal.ai Flux</span>
                         </div>
                         <span class="text-xs font-medium text-green-600 bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full">Operational</span>
                    </div>
                    <div class="flex items-center justify-between">
                         <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-300">Storage</span>
                         </div>
                         <span class="text-xs font-medium text-blue-600 bg-blue-50 dark:bg-blue-900/20 px-2 py-0.5 rounded-full">Healthy</span>
                    </div>
                </div>
            </div>

            <!-- Pro Tip -->
            <div class="bg-gradient-to-br from-gray-900 to-black text-white rounded-2xl p-6 shadow-lg">
                <div class="mb-4">
                    <span class="bg-white/20 text-xs font-bold px-2 py-1 rounded text-white">PRO TIP</span>
                </div>
                <h4 class="font-bold text-lg mb-2">Better Prompts?</h4>
                <p class="text-sm text-gray-400 mb-4">Use specific lighting terms like "Rembrandt lighting" or "Golden Hour" to dramatically improve your product shots.</p>
                <button class="w-full py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm font-medium transition-colors">
                    Read Guide
                </button>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight tracking-tight">
                {{ __('Virtual Models') }}
            </h2>
            @hasanyrole('Admin|Manager')
            <a href="{{ route('storage.models.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 ease-in-out transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Add New Model') }}
            </a>
            @endhasanyrole
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-black min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.300ms class="mb-8 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-green-800 dark:text-green-300 font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            @if($models->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 bg-white dark:bg-zinc-900 rounded-3xl border border-dashed border-gray-300 dark:border-zinc-700">
                    <div class="bg-indigo-50 dark:bg-indigo-900/30 p-4 rounded-full mb-4">
                        <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">No models found</h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400 text-sm">System needs at least one model preset.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($models as $model)
                        <div class="group relative bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300">
                            <!-- Image -->
                            <div class="aspect-w-3 aspect-h-4 bg-gray-200 dark:bg-zinc-800 relative">
                                <img src="{{ asset('storage/' . $model->image_path) }}" alt="{{ $model->name }}" class="w-full h-full object-cover">
                                @if(!$model->is_active)
                                    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full font-bold">Inactive</div>
                                @endif
                                
                                <!-- Admin Actions Overlay -->
                                @hasanyrole('Admin|Manager')
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center space-x-3">
                                        <!-- Edit (placeholder) -->
                                        <!-- <a href="#" class="p-2 bg-white/90 rounded-full hover:bg-white text-gray-800 transition-colors shadow-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a> -->
                                        <form action="{{ route('storage.models.destroy', $model) }}" method="POST" onsubmit="return confirm('Delete this model?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-500/90 rounded-full hover:bg-red-600 text-white transition-colors shadow-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                @endhasanyrole
                            </div>
                            
                            <!-- Bare Footer info -->
                            <div class="p-4 border-t border-gray-100 dark:border-zinc-800">
                                <h3 class="font-bold text-gray-900 dark:text-white text-lg">{{ $model->name }}</h3>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-zinc-800 rounded text-xs text-gray-600 dark:text-gray-400 font-medium">{{ $model->gender }}</span>
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-zinc-800 rounded text-xs text-gray-600 dark:text-gray-400 font-medium">{{ $model->ethnicity }}</span>
                                    @if($model->age_range)
                                        <span class="px-2 py-1 bg-gray-100 dark:bg-zinc-800 rounded text-xs text-gray-600 dark:text-gray-400 font-medium">{{ $model->age_range }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $models->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

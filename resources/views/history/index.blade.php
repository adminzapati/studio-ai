<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">{{ __('Activity History') }}</h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Filters Section -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-6">
            <form method="GET" action="{{ route('history.index') }}" class="space-y-4">
                <!-- Search Bar -->
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">Search</label>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search activities..."
                            class="w-full border border-gray-300 dark:border-zinc-600 rounded-xl px-4 py-2 text-sm bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                    </div>
                    <div class="flex gap-2 items-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors h-10 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </button>
                        @if(request()->hasAny(['search', 'module', 'action', 'date_from', 'date_to']))
                        <a href="{{ route('history.index') }}" class="px-4 py-2 border border-gray-300 dark:border-zinc-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors h-10 flex items-center">
                            Clear
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Filter Pills -->
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Filters:</span>
                    
                    <!-- Module Filter -->
                    <select name="module" onchange="this.form.submit()" class="text-sm border border-gray-300 dark:border-zinc-600 rounded-lg px-3 py-1 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Modules</option>
                        @foreach($modules as $module)
                        <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $module)) }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Action Filter -->
                    <select name="action" onchange="this.form.submit()" class="text-sm border border-gray-300 dark:border-zinc-600 rounded-lg px-3 py-1 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ ucfirst($action) }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Date Filters -->
                    <input type="date" name="date_from" value="{{ request('date_from') }}" onchange="this.form.submit()" class="text-sm border border-gray-300 dark:border-zinc-600 rounded-lg px-3 py-1 bg-white dark:bg-zinc-800 dark:text-white">
                    <span class="text-gray-400">to</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" onchange="this.form.submit()" class="text-sm border border-gray-300 dark:border-zinc-600 rounded-lg px-3 py-1 bg-white dark:bg-zinc-800 dark:text-white">
                </div>
            </form>
        </div>

        <!-- Activity Table -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm overflow-hidden">
            @if($activities->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-zinc-800/50 border-b border-gray-200 dark:border-zinc-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Preview</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Activity</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Module</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Time</th>
                            @role('Admin')
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider"></th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody id="activity-table-body" class="divide-y divide-gray-100 dark:divide-zinc-800">
                        @php $imageIndex = 0; @endphp
                        @foreach($activities as $activity)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <!-- Thumbnail -->
                            <td class="px-6 py-4">
                                @if($activity->thumbnail_path)
                                <div class="group relative w-16 h-16 rounded-lg overflow-hidden bg-gray-100 dark:bg-zinc-700 border border-gray-200 dark:border-zinc-600">
                                    <img src="{{ Storage::disk('public')->url($activity->thumbnail_path) }}" 
                                         alt="Activity thumbnail" 
                                         class="w-full h-full object-cover"
                                         loading="lazy">
                                    
                                    <!-- Eye Icon Overlay -->
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                        <button type="button" 
                                                class="view-image-btn p-1.5 bg-white/90 rounded-full hover:bg-white text-gray-800 transition-colors shadow-sm"
                                                data-image-index="{{ $imageIndex }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @php $imageIndex++; @endphp
                                @else
                                <div class="w-16 h-16 rounded-lg bg-gray-100 dark:bg-zinc-700 border border-gray-200 dark:border-zinc-600 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $activity->actionIcon }}"/>
                                    </svg>
                                </div>
                                @endif
                            </td>

                            <!-- Description -->
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->description }}</p>
                                @if($activity->metadata)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">
                                    {{ isset($activity->metadata['prompt']) ? Str::limit($activity->metadata['prompt'], 60) : '' }}
                                </p>
                                @endif
                            </td>

                            <!-- Module Badge -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->moduleBadgeColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $activity->module)) }}
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $activity->actionIcon }}"/>
                                    </svg>
                                    <span class="text-sm font-medium">{{ ucfirst($activity->action_type) }}</span>
                                </div>
                            </td>

                            <!-- Time -->
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <span title="{{ $activity->created_at->format('Y-m-d H:i:s') }}">
                                    {{ $activity->timeAgo }}
                                </span>
                            </td>

                            @role('Admin')
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('history.destroy', $activity) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this activity? This cannot be undone.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-700" title="Delete Activity">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                            @endrole
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100 dark:border-zinc-800">
                {{ $activities->links() }}
            </div>
            @else
            <!-- Empty State -->
            <div class="py-16 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Activity Yet</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Start using features to see your activity history here.</p>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Viewer.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.getElementById('activity-table-body');
            if (tableBody) {
                const viewer = new Viewer(tableBody, {
                    toolbar: {
                        zoomIn: 1,
                        zoomOut: 1,
                        oneToOne: 1,
                        reset: 1,
                        rotateLeft: 1,
                        rotateRight: 1,
                        flipHorizontal: 1,
                        flipVertical: 1,
                    },
                    title: false,
                    transition: false,
                    filter(image) {
                        // Only view images that are inside the thumbnail container
                        return image.parentElement.classList.contains('group'); 
                    }
                });

                // Handle eye button clicks
                document.querySelectorAll('.view-image-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation(); // Prevent row click if any
                        const index = parseInt(btn.getAttribute('data-image-index'));
                        viewer.view(index);
                    });
                });
            }
        });
    </script>
</x-app-layout>

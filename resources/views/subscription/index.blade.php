<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
            {{ __('Subscription Plans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($plans as $plan)
                    @php
                        $isCurrent = $currentSubscription && $currentSubscription->subscription_plan_id == $plan->id;
                    @endphp
                    <div class="relative flex flex-col p-6 bg-white dark:bg-zinc-900 rounded-2xl border {{ $isCurrent ? 'border-indigo-500 ring-2 ring-indigo-500' : 'border-gray-200 dark:border-zinc-800' }} shadow-sm hover:shadow-lg transition-all duration-300">
                        @if($isCurrent)
                            <div class="absolute top-0 right-0 -mt-2 -mr-2 px-3 py-1 bg-indigo-500 text-white text-xs font-bold rounded-full uppercase tracking-wide">
                                Current
                            </div>
                        @endif

                        <div class="mb-5">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $plan->name }}</h3>
                            <div class="mt-4 flex items-baseline text-gray-900 dark:text-white">
                                <span class="text-3xl font-extrabold tracking-tight">${{ number_format($plan->price, 0) }}</span>
                                <span class="ml-1 text-xl font-semibold text-gray-500">/month</span>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">{{ number_format($plan->credits_monthly) }} credits/month</p>
                        </div>

                        <ul role="list" class="flex-1 space-y-4 mb-6">
                            <!-- Modules List -->
                            @if(in_array('*', $plan->modules ?? []))
                                <li class="flex items-start">
                                    <svg class="flex-shrink-0 w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">All Modules Access</span>
                                </li>
                            @else
                                <!-- List specific modules (simplified logic for now) -->
                                @php
                                    $allModules = \App\Models\FeatureModule::where('is_enabled', true)->pluck('name', 'slug')->toArray();
                                @endphp
                                @foreach($allModules as $slug => $name)
                                    <li class="flex items-start">
                                        @if(in_array($slug, $plan->modules ?? []))
                                            <svg class="flex-shrink-0 w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $name }}</span>
                                        @else
                                            <svg class="flex-shrink-0 w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                            <span class="ml-3 text-sm text-gray-400">{{ $name }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            @endif

                            <!-- Extra Features (from JSON) -->
                            @foreach($plan->features ?? [] as $feature)
                                <li class="flex items-start">
                                    <svg class="flex-shrink-0 w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-auto">
                            @if($isCurrent)
                                <button disabled class="w-full block bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg py-2 text-sm font-semibold text-gray-400 text-center cursor-not-allowed">
                                    Current Plan
                                </button>
                            @elseif(isset($pendingRequest) && $pendingRequest->subscription_plan_id == $plan->id)
                                <button disabled class="w-full block bg-amber-100 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-lg py-2 text-sm font-semibold text-amber-600 dark:text-amber-400 text-center cursor-wait">
                                    Waiting Approval
                                </button>
                            @else
                                <form action="{{ route('subscription.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                    <button type="submit" class="w-full block bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg py-2 text-sm font-semibold text-center transition-colors">
                                        {{ $plan->price > 0 ? 'Upgrade' : 'Select Free' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Credit System Info Section -->
            @role('Admin')
            <div class="mt-16 bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Credit System Design</h3>
                    <p class="mt-2 text-sm text-gray-500">1 Credit = 1 Low Quality 1024x1024 Image ($0.009)</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-zinc-800 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Quality</th>
                                <th scope="col" class="px-6 py-3">Ratio</th>
                                <th scope="col" class="px-6 py-3">Cost</th>
                                <th scope="col" class="px-6 py-3">Credits</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white border-b dark:bg-zinc-900 dark:border-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-800">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">Low</td>
                                <td class="px-6 py-4">1:1</td>
                                <td class="px-6 py-4">$0.009</td>
                                <td class="px-6 py-4">1</td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-zinc-900 dark:border-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-800">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">Low</td>
                                <td class="px-6 py-4">2:3 / 3:2</td>
                                <td class="px-6 py-4">$0.013</td>
                                <td class="px-6 py-4">1.5</td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-zinc-900 dark:border-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-800">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">Medium</td>
                                <td class="px-6 py-4">1:1</td>
                                <td class="px-6 py-4">$0.034</td>
                                <td class="px-6 py-4">4</td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-zinc-900 dark:border-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-800">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">Medium</td>
                                <td class="px-6 py-4">2:3 / 3:2</td>
                                <td class="px-6 py-4">$0.051</td>
                                <td class="px-6 py-4">6</td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-zinc-900 dark:border-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-800">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">High</td>
                                <td class="px-6 py-4">1:1</td>
                                <td class="px-6 py-4">$0.133</td>
                                <td class="px-6 py-4">15</td>
                            </tr>
                             <tr class="bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">High</td>
                                <td class="px-6 py-4">2:3 / 3:2</td>
                                <td class="px-6 py-4">$0.200</td>
                                <td class="px-6 py-4">22</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endrole

            <!-- Feature Comparison Matrix -->
            <div class="mt-12 bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Module Access by Plan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-gray-500 dark:text-gray-400 text-center">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-zinc-800 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left">Module</th>
                                @foreach($plans as $plan)
                                    <th scope="col" class="px-6 py-4">{{ $plan->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $comparisonModules = [
                                    'products_virtual' => 'Products Virtual',
                                    'batch' => 'Batch Processor',
                                    'beautifier' => 'Beautifier',
                                    'virtual_model' => 'Virtual Model',
                                    'prompts' => 'Prompts Library',
                                    'images' => 'Images Library',
                                    'model_presets' => 'Model Presets',
                                    'history' => 'History',
                                    'support' => 'Priority Support', 
                                ];
                            @endphp

                            @foreach($comparisonModules as $slug => $label)
                                <tr class="bg-white border-b dark:bg-zinc-900 dark:border-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-800">
                                    <td class="px-6 py-4 text-left font-medium text-gray-900 dark:text-white">{{ $label }}</td>
                                    @foreach($plans as $plan)
                                        <td class="px-6 py-4">
                                            @php
                                                // Check Access
                                                $hasAccess = false;
                                                if ($slug === 'support') {
                                                    $hasAccess = $plan->slug === 'enterprise';
                                                } else {
                                                    $modules = $plan->modules ?? [];
                                                    $hasAccess = in_array('*', $modules) || in_array($slug, $modules);
                                                }
                                            @endphp

                                            @if($hasAccess)
                                                 <div class="flex justify-center items-center">
                                                    <div class="w-6 h-6 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex justify-center items-center">
                                                     <div class="w-6 h-6 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- FAQ or Info Section -->
            <div class="mt-12 text-center">
                <p class="text-gray-500">Need a custom plan? <a href="#" class="text-indigo-600 hover:underline">Contact Sales</a></p>
            </div>
            
        </div>
    </div>
</x-app-layout>

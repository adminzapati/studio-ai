<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight">
            {{ __('Auto Prompt Wizard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-black min-h-screen" 
         x-data="promptWizard({{ $steps->toJson() }})">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="relative pt-1">
                    <div class="flex mb-2 items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-indigo-600 bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-200">
                                Step <span x-text="currentStep"></span> of 5
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-semibold inline-block text-indigo-600 dark:text-indigo-400" x-text="stepTitles[currentStep]">
                            </span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200 dark:bg-zinc-800">
                        <div :style="'width: ' + ((currentStep / 5) * 100) + '%'" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500 transition-all duration-500 ease-in-out"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-zinc-800">
                <div class="p-8">

                    <!-- Steps 1-4: Selection Grid -->
                    <template x-for="stepNum in [1, 2, 3, 4]">
                        <div x-show="currentStep === stepNum" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                            
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6" x-text="stepTitles[stepNum]"></h3>
                            
                            <!-- Groups (Categories) -->
                            <template x-for="(options, category) in stepsData[stepNum] || {}">
                                <div class="mb-8">
                                    <h4 class="text-md font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4" x-text="category"></h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        <template x-for="option in options">
                                            <div @click="toggleSelection(stepNum, option)"
                                                 :class="isSelected(stepNum, option) ? 'ring-2 ring-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'hover:bg-gray-50 dark:hover:bg-zinc-800'"
                                                 class="cursor-pointer border border-gray-200 dark:border-zinc-700 rounded-xl p-4 transition-all duration-200 flex flex-col items-center text-center h-full">
                                                
                                                <!-- Icon Placeholder (if no SVG logic yet) -->
                                                <div class="mb-3 text-indigo-500 dark:text-indigo-400">
                                                    <!-- Simple circle for now, or dynamic icon map -->
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                                </div>
                                                
                                                <h5 class="font-bold text-gray-900 dark:text-gray-100 mb-1" x-text="option.label"></h5>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2" x-text="option.value"></p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <!-- Step 5: Finish & Save -->
                    <div x-show="currentStep === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Review & Save</h3>
                        
                        <div class="bg-indigo-50 dark:bg-indigo-900/10 rounded-2xl p-6 border border-indigo-100 dark:border-indigo-800 mb-6">
                             <div class="mb-2 flex justify-between items-center">
                                <label class="text-sm font-bold text-indigo-900 dark:text-indigo-200 uppercase tracking-widest">Final Prompt</label>
                                <button @click="copyToClipboard()" class="text-xs flex items-center text-indigo-600 dark:text-indigo-400 hover:underline">
                                    <span x-text="copyBtnText"></span>
                                </button>
                             </div>
                            <textarea x-model="finalPrompt" rows="8" class="w-full bg-transparent border-0 focus:ring-0 text-gray-800 dark:text-gray-200 font-mono text-lg leading-relaxed resize-none p-0"></textarea>
                        </div>

                        <!-- Save Form -->
                        <div class="bg-gray-50 dark:bg-zinc-800 rounded-xl p-6">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-4">Save to Library</h4>
                            <form action="{{ route('storage.prompts.store') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                                        <input type="text" name="name" required placeholder="My Awesome Prompt" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-zinc-700 dark:text-white sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                        <select name="category" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-zinc-700 dark:text-white sm:text-sm">
                                            <option value="Fashion">Fashion</option>
                                            <option value="E-Commerce">E-Commerce</option>
                                            <option value="Portrait">Portrait</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="prompt" :value="finalPrompt">
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition-transform hover:-translate-y-0.5">
                                        Save Prompt
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Navigation Footer -->
                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-zinc-800 flex justify-between items-center">
                        <button @click="prevStep()" 
                                x-show="currentStep > 1"
                                class="px-6 py-2 rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-zinc-800 font-medium transition-colors">
                            Back
                        </button>
                        <div x-show="currentStep === 1"></div> <!-- Spacer -->

                        <button @click="nextStep()" 
                                x-show="currentStep < 5"
                                class="px-8 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-lg shadow-indigo-500/30 transition-transform hover:-translate-y-0.5 flex items-center">
                            Next Step
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Logic -->
    <script>
        function promptWizard(initialData) {
            return {
                currentStep: 1,
                stepsData: initialData, // grouped by step
                selections: {},
                finalPrompt: '',
                copyBtnText: 'Copy to Clipboard',
                stepTitles: {
                    1: 'Core Information',
                    2: 'Presenting & Background',
                    3: 'Technical Details',
                    4: 'Polishing & Mood',
                    5: 'Final Review'
                },

                init() {
                    // Group data locally if needed, but we passed grouped data
                    // Initialize selections arrays
                    [1, 2, 3, 4].forEach(s => this.selections[s] = []);
                },

                isSelected(step, option) {
                    return this.selections[step].some(o => o.id === option.id);
                },

                toggleSelection(step, option) {
                    const index = this.selections[step].findIndex(o => o.id === option.id);
                    if (index > -1) {
                        this.selections[step].splice(index, 1);
                    } else {
                        // For core type (Step 1), maybe single select? Let's allow multi for flexibility,
                        // but usually "Type" is single.
                        // Imposing Single Select for Category "Type" in Step 1?
                        // For simplicity, Multi everywhere for now.
                        this.selections[step].push(option);
                    }
                    this.buildPrompt();
                },

                nextStep() {
                    if (this.currentStep < 5) {
                        this.currentStep++;
                        if (this.currentStep === 5) this.buildPrompt();
                    }
                },

                prevStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                    }
                },

                buildPrompt() {
                    let parts = [];
                    // Order: Step 1 -> 2 -> 3 -> 4
                    [1, 2, 3, 4].forEach(s => {
                        this.selections[s].forEach(opt => {
                            parts.push(opt.value);
                        });
                    });
                    this.finalPrompt = parts.join(', ');
                },

                copyToClipboard() {
                    navigator.clipboard.writeText(this.finalPrompt);
                    this.copyBtnText = 'Copied!';
                    setTimeout(() => this.copyBtnText = 'Copy to Clipboard', 2000);
                }
            }
        }
    </script>
</x-app-layout>

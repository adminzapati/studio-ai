<x-app-layout padding="px-10" maxWidth="max-w-full">
    <div class="h-[calc(100vh-65px)] bg-gray-50 dark:bg-black flex flex-col md:flex-row gap-6 p-6 overflow-hidden w-full" 
         x-data="promptCreator({
              data: {{ isset($prompt) ? $prompt : 'null' }},
              imageUrl: '{{ isset($prompt) && $prompt->image_path ? Storage::url($prompt->image_path) : '' }}'
          })">
        
        <!-- Left Pane: Input Methods -->
        <div class="md:w-[30%] w-full flex flex-col bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
            <!-- Tabs -->
            <div class="flex border-b border-gray-200 dark:border-zinc-800">
                <button @click="tab = 'image'" 
                        :class="{'border-indigo-500 text-indigo-600 dark:text-indigo-400': tab === 'image', 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400': tab !== 'image'}"
                        class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>From Image</span>
                    </div>
                </button>
                <button @click="tab = 'wizard'" 
                        :class="{'border-indigo-500 text-indigo-600 dark:text-indigo-400': tab === 'wizard', 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400': tab !== 'wizard'}"
                        class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        <span>Wizard</span>
                    </div>
                </button>
                <button @click="tab = 'manual'" 
                        :class="{'border-indigo-500 text-indigo-600 dark:text-indigo-400': tab === 'manual', 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400': tab !== 'manual'}"
                        class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Manual</span>
                    </div>
                </button>
            </div>
            <!-- Tab Contents -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                <!-- Image Tab -->
                <div x-show="tab === 'image'" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload Reference Image</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 dark:border-zinc-700 border-dashed rounded-2xl hover:border-indigo-400 dark:hover:border-indigo-500 transition-colors duration-200"
                             @dragover.prevent="dragover = true"
                             @dragleave.prevent="dragover = false"
                             @drop.prevent="handleDrop($event)"
                             :class="{'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10': dragover}">
                            <div class="space-y-1 text-center">
                                <template x-if="!imagePreview">
                                    <div class="flex flex-col items-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                            <label for="file-upload" class="relative cursor-pointer bg-white dark:bg-transparent rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload a file</span>
                                                <input id="file-upload" name="file-upload" type="file" class="sr-only" accept="image/*" @change="handleFileSelect($event)">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </template>
                                <template x-if="imagePreview">
                                    <div class="relative">
                                        <img :src="imagePreview" class="mx-auto h-64 object-contain rounded-lg shadow-sm" alt="Preview">
                                        <button @click="removeImage()" class="absolute top-2 right-2 p-1 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition-colors cursor-pointer" aria-label="Remove image">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <!-- File Size Error Message -->
                    <div x-show="fileError" x-transition class="mt-3 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm text-red-700 dark:text-red-300" x-text="fileError"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Instructions (Optional)</label>
                        <textarea x-model="imageNotes" rows="4" class="w-full rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 focus:ring-indigo-500 focus:border-indigo-500" placeholder="E.g., Focus on the texture of the fabric..."></textarea>
                    </div>
                </div>

                <!-- Wizard Tab (Under Development) -->
                <div x-show="tab === 'wizard'" class="flex flex-col items-center justify-center h-full py-16">
                    <div class="p-4 bg-amber-100 dark:bg-amber-900/30 rounded-full mb-4">
                        <svg class="w-10 h-10 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Under Development</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center max-w-xs">
                        The Wizard feature is currently being developed. Please use "From Image" or "Manual" methods instead.
                    </p>
                </div>

                <!-- Manual Tab -->
                <div x-show="tab === 'manual'" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Raw Prompt</label>
                        <textarea x-model="manualPrompt" rows="8" class="w-full rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your raw idea here..."></textarea>
                        <p class="mt-2 text-xs text-gray-500">Gemini will optimize and expand this prompt for you.</p>
                    </div>
                </div>
            </div>

            <!-- Footer for Input -->
            <div class="p-6 border-t border-gray-200 dark:border-zinc-800">
                <button @click="generate()" 
                        :disabled="isGenerating || !canGenerate"
                        :class="{'opacity-50 cursor-not-allowed': isGenerating || !canGenerate}"
                        class="w-full flex justify-center items-center gap-2 py-3.5 px-6 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 cursor-pointer disabled:cursor-not-allowed"
                        aria-label="Generate prompt with Gemini">
                    <span x-show="!isGenerating">Generate with Gemini</span>
                    <span x-show="isGenerating" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Thinking...
                    </span>
                </button>
            </div>
        </div>

        <!-- Right Pane: Result -->
        <div class="md:w-[70%] w-full flex flex-col bg-gray-50 dark:bg-black p-6 overflow-y-auto">
            <template x-if="!generatedPrompt">
                <div class="flex-1 flex flex-col items-center justify-center text-center text-gray-500 dark:text-gray-400">
                    <div class="bg-white dark:bg-zinc-900 p-6 rounded-full shadow-sm mb-4">
                        <svg class="w-12 h-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white font-heading">Ready to Generate</h3>
                    <p class="mt-2 max-w-sm">Use the tools on the left to create a high-quality prompt tailored for fashion e-commerce.</p>
                </div>
            </template>

            <div x-show="generatedPrompt" class="flex flex-col h-full space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6 flex-1 flex flex-col">
                    <!-- Analysis Section (Only for Image) -->
                    <div x-show="imageAnalysis" class="mb-6 pb-6 border-b border-gray-100 dark:border-zinc-800">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-3 flex items-center font-heading">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Image Analysis
                        </h3>
                        <div class="bg-gray-50 dark:bg-zinc-800/50 rounded-xl p-4 text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line" x-text="imageAnalysis"></div>
                    </div>

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-900 dark:text-white font-heading">Generated Prompt</h3>
                        <div class="flex space-x-2">
                            <button @click="copyToClipboard()" class="p-2 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer" title="Copy" aria-label="Copy to clipboard">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </button>
                        </div>
                    </div>
                    <textarea x-model="generatedPrompt" class="flex-1 w-full rounded-xl border-gray-300 dark:border-zinc-800 dark:bg-zinc-900 focus:ring-indigo-500 focus:border-indigo-500 font-mono text-sm leading-relaxed p-4 resize-none"></textarea>
                </div>

                <!-- Save Form -->
                <!-- Save Form -->
                <form action="{{ isset($prompt) ? route('storage.prompts.update', $prompt->id) : route('storage.prompts.store') }}" method="POST" class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
                    @csrf
                    @if(isset($prompt))
                        @method('PUT')
                    @endif
                    <input type="hidden" name="prompt" x-model="generatedPrompt">
                    <input type="hidden" name="image_base64" :value="imageBase64">
                    <input type="hidden" name="method" :value="tab">
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                            <input type="text" name="name" x-model="promptName" required class="w-full rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                            <select name="category" x-model="promptCategory" class="w-full rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="Fashion">Fashion</option>
                                <option value="E-Commerce">E-Commerce</option>
                                <option value="Portrait">Portrait</option>
                                <option value="Product">Product</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-1 group/fav">
                            <button type="button" 
                                @click="isFavorite = !isFavorite"
                                class="p-2 rounded-xl transition-all duration-200 focus:outline-none flex items-center gap-2"
                                :class="isFavorite ? 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-400' : 'text-gray-400 hover:bg-gray-50 dark:hover:bg-zinc-800'">
                                <svg class="w-5 h-5 transition-transform" :class="isFavorite ? 'scale-110 fill-current' : 'fill-none stroke-current'" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                <span class="text-sm font-medium" :class="isFavorite ? 'text-yellow-700 dark:text-yellow-400' : 'text-gray-600 dark:text-gray-400'">Favorite</span>
                            </button>
                            <input type="hidden" name="is_favorite" :value="isFavorite ? 1 : 0">
                        </div>
                        <button type="submit" 
                            :disabled="!generatedPrompt"
                            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-medium text-sm rounded-xl shadow-lg shadow-indigo-500/30 transition-all cursor-pointer">
                            {{ isset($prompt) ? 'Update Prompt' : 'Save to Library' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Script -->
    <script>
        function promptCreator(config = {}) {
            const initial = config.data || {};
            return {
                tab: initial.method || (initial.image_path || config.imageUrl ? 'image' : (initial.prompt ? 'manual' : 'image')),
                dragover: false,
                imagePreview: config.imageUrl || null,
                imageFile: null,
                imageNotes: '',
                manualPrompt: '',
                generatedPrompt: initial.prompt || '',
                promptName: initial.name || '',
                promptCategory: initial.category || 'Fashion',
                isFavorite: !!initial.is_favorite,
                imageAnalysis: '',
                isGenerating: false,
                fileError: '',

                get imageBase64() {
                    // Only send base64 if it's a new upload (Data URL)
                    return (this.imagePreview && this.imagePreview.startsWith('data:')) ? this.imagePreview : '';
                },

                get canGenerate() {
                    if (this.tab === 'image') return !!this.imageFile && !this.fileError;
                    if (this.tab === 'wizard') return false; // Disabled
                    if (this.tab === 'manual') return !!this.manualPrompt;
                    return false;
                },

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) this.processFile(file);
                },

                handleDrop(event) {
                    this.dragover = false;
                    const file = event.dataTransfer.files[0];
                    if (file) this.processFile(file);
                },

                processFile(file) {
                    this.fileError = '';
                    if (!file.type.match('image.*')) {
                        this.fileError = 'Please upload an image file (PNG, JPG, GIF).';
                        return;
                    }
                    const maxSizeBytes = 2 * 1024 * 1024; // 2MB
                    if (file.size > maxSizeBytes) {
                        this.fileError = `File size exceeds 2MB limit. Your file: ${(file.size / 1024 / 1024).toFixed(2)}MB`;
                        return;
                    }
                    this.imageFile = file;
                    const reader = new FileReader();
                    reader.onload = (e) => this.imagePreview = e.target.result;
                    reader.readAsDataURL(file);
                },

                removeImage() {
                    this.imageFile = null;
                    this.imagePreview = null;
                    this.fileError = '';
                },

                async generate() {
                    this.isGenerating = true;
                    this.generatedPrompt = '';

                    try {
                        this.imageAnalysis = ''; // Reset analysis
                        let payload = {
                            type: this.tab,
                            data: {}
                        };

                        if (this.tab === 'image') {
                            payload.data = {
                                image: this.imagePreview.split(',')[1], // Send base64 without prefix
                                notes: this.imageNotes
                            };
                        } else if (this.tab === 'wizard') {
                            payload.data = this.wizardData;
                        } else {
                            payload.data = { prompt: this.manualPrompt };
                        }

                        const response = await fetch('{{ route('storage.prompts.generate') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(payload)
                        });

                        const result = await response.json();

                        if (result.success) {
                            if (result.prompt.analysis) {
                                // Handle if analysis is object or string
                                if (typeof result.prompt.analysis === 'object') {
                                    this.imageAnalysis = Object.entries(result.prompt.analysis)
                                        .map(([key, value]) => `• ${key}: ${value}`)
                                        .join('\n');
                                } else {
                                    this.imageAnalysis = result.prompt.analysis;
                                }
                                this.generatedPrompt = result.prompt.prompt;
                            } else {
                                this.generatedPrompt = result.prompt.prompt || result.prompt;
                            }
                        } else {
                            alert('Error: ' + result.message);
                        }
                    } catch (error) {
                        console.error(error);
                        alert('Something went wrong. Please check console.');
                    } finally {
                        this.isGenerating = false;
                    }
                },

                copyToClipboard() {
                    navigator.clipboard.writeText(this.generatedPrompt);
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Prompt copied to clipboard!', type: 'success' } }));
                }
            }
        }
    </script>
</x-app-layout>

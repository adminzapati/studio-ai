<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">{{ __('Products Virtual') }}</h2>
    </x-slot>

    <div x-data="productsVirtualApp()" class="space-y-6">
        <!-- Quota Display -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    @if($isDevMode)
                    <span class="px-3 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                        DEV MODE
                    </span>
                    @endif
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Daily Quota:</span>
                        <span class="font-semibold" :class="quota.daily === -1 ? 'text-green-600' : (quota.daily > 0 ? 'text-blue-600' : 'text-red-600')">
                            <span x-text="quota.daily === -1 ? 'Unlimited' : quota.daily"></span>
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total:</span>
                        <span class="font-semibold" :class="quota.total === -1 ? 'text-green-600' : (quota.total > 0 ? 'text-blue-600' : 'text-red-600')">
                            <span x-text="quota.total === -1 ? 'Unlimited' : quota.total"></span>
                        </span>
                    </div>
                </div>
                <!-- Debug Modal Button -->
                <button x-show="showDebugButton" @click="showDebugModal = true" 
                        class="px-3 py-1 bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    View Debug Info
                </button>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column: Upload Section -->
            <div class="space-y-6">
                
                <!-- Mode Switcher -->
                <div class="flex p-1 bg-gray-100 dark:bg-zinc-800 rounded-xl">
                    <button @click="inputMode = 'upload'; resetState();" 
                            :class="inputMode === 'upload' ? 'bg-white dark:bg-zinc-700 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'" 
                            class="flex-1 py-2 text-sm font-medium rounded-lg transition-all">
                        Upload Reference Image
                    </button>
                    <button @click="inputMode = 'prompt'; resetState();" 
                            :class="inputMode === 'prompt' ? 'bg-white dark:bg-zinc-700 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'" 
                            class="flex-1 py-2 text-sm font-medium rounded-lg transition-all">
                        Select from Library
                    </button>
                </div>

                <!-- Target Model Upload (Upload Mode) -->
                <div x-show="inputMode === 'upload'" class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Target Model
                        </h3>
                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full">Ref Image</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Upload the model/scene you want to dress.</p>
                    
                    <!-- Upload Zone -->
                    <div x-show="!modelImage" 
                         @click="$refs.modelInput.click()" 
                         style="display: block;"
                         class="border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-xl p-8 text-center cursor-pointer hover:border-blue-400 transition-colors">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-blue-600 font-medium">Click to Upload Model Image</span>
                        <p class="text-xs text-gray-400 mt-2">PNG, JPG up to 2MB</p>
                        <input type="file" x-ref="modelInput" @change="handleModelUpload" accept="image/*" class="hidden">
                    </div>
                    
                    <!-- Preview -->
                    <div x-show="modelImage" style="display: none;" class="relative">
                        <img :src="modelImagePreview" class="w-full h-64 object-cover rounded-xl">
                        <button @click="removeModelImage" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Selected Prompt (Prompt Mode) -->
                <div x-show="inputMode === 'prompt'" class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-6" style="display: none;">
                     <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Selected Prompt
                        </h3>
                         <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full">From Library</span>
                    </div>
                    
                    <div x-show="!selectedPrompt" class="text-center py-8">
                        <button @click="openLibrary" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl transition-colors shadow-lg shadow-purple-500/30 flex items-center gap-2 mx-auto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Browse Prompt Library
                        </button>
                        <p class="text-sm text-gray-500 mt-3">Select a prompt to reuse its composition</p>
                    </div>

                    <div x-show="selectedPrompt" class="border border-purple-200 dark:border-purple-900/50 bg-purple-50 dark:bg-purple-900/10 rounded-xl p-4">
                        <!-- Selected Prompt Preview -->
                        <div class="flex gap-4">
                             <div class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden bg-gray-200 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700">
                                <template x-if="selectedPrompt?.image_url">
                                    <img :src="selectedPrompt.image_url" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!selectedPrompt?.image_url">
                                    <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-zinc-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </template>
                             </div>
                             <div class="flex-grow min-w-0">
                                <h4 x-text="selectedPrompt?.name" class="font-medium text-gray-900 dark:text-white truncate"></h4>
                                <span x-text="selectedPrompt?.category" class="text-xs px-2 py-0.5 bg-white dark:bg-zinc-800 rounded text-gray-600 dark:text-gray-300 border border-gray-100 dark:border-zinc-700 inline-block mb-1"></span>
                                <p x-text="selectedPrompt?.prompt" class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 font-mono"></p>
                             </div>
                        </div>
                        <button @click="openLibrary" class="w-full mt-3 py-2 text-sm text-purple-600 bg-white dark:bg-zinc-800 border border-purple-100 dark:border-purple-900/30 rounded-lg hover:bg-purple-50 transition-colors">
                            Change Prompt
                        </button>
                    </div>
                </div>

                <!-- Products Upload -->
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Products
                        </h3>
                        <span class="text-xs px-2 py-1 bg-pink-100 text-pink-700 rounded-full">Max 4</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Upload angles of the product to wear.</p>
                    
                    <!-- Products Grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <template x-for="(img, index) in productImages" :key="index">
                            <div class="relative">
                                <img :src="productPreviews[index]" class="w-full h-32 object-cover rounded-xl border border-gray-200 dark:border-zinc-700">
                                <button @click="removeProductImage(index)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-0.5 hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        
                        <!-- Add Button -->
                        <div x-show="productImages.length < 4" 
                             @click="$refs.productInput.click()"
                             style="display: flex;"
                             class="border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-xl h-32 flex-col items-center justify-center cursor-pointer hover:border-blue-400 transition-colors">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="text-sm text-gray-500 mt-1">Click to Add Product</span>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG up to 2MB</p>
                            <input type="file" x-ref="productInput" @change="handleProductUpload" accept="image/*" multiple class="hidden">
                        </div>
                    </div>
                </div>

                <!-- Analyze/Generate Button -->
                <button @click="analyzeImages" :disabled="!canAnalyze || isAnalyzing" 
                        class="w-full py-4 rounded-xl font-semibold text-white transition-all"
                        :class="canAnalyze && !isAnalyzing ? 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700' : 'bg-gray-300 cursor-not-allowed'">
                    <span x-show="!isAnalyzing" class="flex items-center justify-center gap-2">
                        <template x-if="inputMode === 'upload'">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                Upload Model & Product
                            </span>
                        </template>
                        <template x-if="inputMode === 'prompt'">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                                Use Selected Prompt & Products
                            </span>
                        </template>
                    </span>
                    <span x-show="isAnalyzing" class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>

                <!-- Generate Again Button (shown after generation) -->
                <button x-show="resultImage" @click="regenerate" :disabled="isGenerating"
                        class="w-full py-4 rounded-xl font-semibold text-white bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Generate Again
                </button>
            </div>

            <!-- Right Column: Result & Generation -->
            <div class="space-y-6">
                <!-- Result Prompt Display -->
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Result Prompt</h3>
                        <span class="text-xs px-3 py-1 rounded-full font-medium"
                              :class="promptStatus === 'waiting' ? 'bg-gray-100 text-gray-600' : (promptStatus === 'completed' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700')"
                              x-text="promptStatus.toUpperCase()"></span>
                    </div>
                    
                    <!-- Waiting State -->
                    <div x-show="promptStatus === 'waiting'" class="border border-gray-200 dark:border-zinc-700 rounded-xl p-4 min-h-[150px] flex items-center justify-center">
                        <p class="text-gray-400 italic font-mono text-sm">Prompt result will appear here after analysis...</p>
                    </div>
                    
                    <!-- Completed State -->
                    <div x-show="promptStatus !== 'waiting'" class="space-y-4">
                        <div class="border border-gray-200 dark:border-zinc-700 rounded-xl p-4 bg-gray-50 dark:bg-zinc-800 font-mono text-sm max-h-[200px] overflow-y-auto">
                            <p x-text="prompt" class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap"></p>
                        </div>
                        
                        <!-- Refine Section -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-pink-600">REFINE PROMPT (OPTIONAL)</label>
                            <textarea x-model="refinedPrompt" rows="6" 
                                      class="w-full border border-gray-300 dark:border-zinc-600 rounded-xl p-3 text-sm bg-white dark:bg-zinc-800 dark:text-white resize-y focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Edit the prompt to refine the generation..."></textarea>
                        </div>
                        
                        <!-- Parameters Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Size / Ratio</label>
                                <select x-model="params.sizeRatio" class="w-full border border-gray-300 dark:border-zinc-600 rounded-lg p-2 text-sm bg-white dark:bg-zinc-800 dark:text-white">
                                    <option value="1:1">Square (1:1)</option>
                                    <option value="2:3">Portrait (2:3)</option>
                                    <option value="3:2">Landscape (3:2)</option>
                                    <option value="4:3">Standard (4:3)</option>
                                    <option value="16:9">Wide (16:9)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Background</label>
                                <select x-model="params.background" class="w-full border border-gray-300 dark:border-zinc-600 rounded-lg p-2 text-sm bg-white dark:bg-zinc-800 dark:text-white">
                                    <option value="auto">Auto</option>
                                    <option value="white">White</option>
                                    <option value="transparent">Transparent</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quality</label>
                                <select x-model="params.quality" class="w-full border border-gray-300 dark:border-zinc-600 rounded-lg p-2 text-sm bg-white dark:bg-zinc-800 dark:text-white">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Format</label>
                                <select x-model="params.format" class="w-full border border-gray-300 dark:border-zinc-600 rounded-lg p-2 text-sm bg-white dark:bg-zinc-800 dark:text-white">
                                    <option value="png">PNG</option>
                                    <option value="jpg">JPG</option>
                                    <option value="webp">WebP</option>
                                </select>
                            </div>
                            
                            <!-- Number of Images (Admin/Manager Only) -->
                            @if(auth()->user()->hasAnyRole(['Admin', 'Manager']))
                            <div class="col-span-2">
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Number of Images</label>
                                    <span class="text-xs font-semibold text-purple-600 bg-purple-100 dark:bg-purple-900/30 dark:text-purple-400 px-2 py-0.5 rounded-full" x-text="params.numImages + (params.numImages > 1 ? ' Images' : ' Image')"></span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-xs text-gray-400 font-medium">1</span>
                                    <input type="range" x-model="params.numImages" min="1" max="4" step="1"
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-zinc-700 accent-purple-600">
                                    <span class="text-xs text-gray-400 font-medium">4</span>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Generate Button -->
                        <button @click="generateImage" :disabled="!canGenerate || isGenerating"
                                class="w-full py-4 rounded-xl font-semibold text-white transition-all flex items-center justify-center gap-2"
                                :class="canGenerate && !isGenerating ? 'bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600' : 'bg-gray-300 cursor-not-allowed'">
                            <svg x-show="!isGenerating" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"/>
                            </svg>
                            <svg x-show="isGenerating" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span x-text="isGenerating ? 'Generating...' : 'Generate Virtual Try-On Result'"></span>
                        </button>
                    </div>
                </div>

                <!-- Result Preview -->
                <div x-show="resultImage" class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Generated Result</h3>
                    <img :src="resultImage" class="w-full rounded-xl">
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-3 mt-4">
                        <button @click="downloadResult" class="flex-1 py-3 rounded-xl font-medium text-gray-700 dark:text-white border border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </button>
                        <button @click="saveToLibrary" class="flex-1 py-3 rounded-xl font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            Save to Library
                        </button>
                    </div>
                </div>

                <!-- Error Display -->
                <div x-show="error" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                    <p class="text-red-700 dark:text-red-400 text-sm" x-text="error"></p>
                </div>
            </div>
        </div>

        <!-- Debug Modal -->
        <div x-show="showDebugModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto" 
             @keydown.escape.window="showDebugModal = false">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDebugModal = false"></div>
                
                <!-- Modal panel -->
                <div class="relative bg-white dark:bg-zinc-900 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-4xl sm:w-full">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                            Debug Information (Dev Mode)
                        </h3>
                        <button @click="showDebugModal = false" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="px-6 py-4 max-h-[60vh] overflow-y-auto">
                        <pre class="bg-gray-100 dark:bg-zinc-800 rounded-lg p-4 text-xs font-mono text-gray-800 dark:text-gray-200 overflow-x-auto whitespace-pre-wrap" x-text="JSON.stringify(debugInfo, null, 2)"></pre>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700 flex justify-between items-center">
                        <button @click="copyDebugInfo()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                            </svg>
                            Copy JSON
                        </button>
                        <button @click="showDebugModal = false" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

    
    <!-- Prompt Library Modal -->
    <div x-show="showLibraryModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         @keydown.escape.window="showLibraryModal = false">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="showLibraryModal = false"></div>
            
            <!-- Modal panel -->
            <div class="relative bg-white dark:bg-zinc-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-4xl sm:w-full border border-gray-100 dark:border-zinc-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Prompt Library
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Select a saved prompt to use immediately</p>
                    </div>
                    <button @click="showLibraryModal = false" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 text-gray-400 hover:text-gray-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="p-6 bg-gray-50 dark:bg-black/50 min-h-[400px]">
                    <!-- Loading State -->
                    <div x-show="libraryLoading" class="flex flex-col items-center justify-center py-20">
                        <svg class="w-10 h-10 text-purple-500 animate-spin mb-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <p class="text-gray-500 font-medium">Loading prompts...</p>
                    </div>

                    <!-- Empty State -->
                    <div x-show="!libraryLoading && libraryPrompts.length === 0" class="flex flex-col items-center justify-center py-20 text-gray-400">
                        <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <p class="text-sm">No saved prompts found.</p>
                    </div>
                    
                    <!-- Grid -->
                    <div x-show="!libraryLoading && libraryPrompts.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="prompt in libraryPrompts" :key="prompt.id">
                            <div @click="selectPrompt(prompt)" 
                                 class="group bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-3 cursor-pointer hover:border-purple-400 hover:ring-2 hover:ring-purple-400/20 transition-all shadow-sm flex items-start gap-4">
                                <!-- Image Thumbnail -->
                                <div class="flex-shrink-0 w-24 h-24 rounded-lg overflow-hidden bg-gray-100 dark:bg-zinc-700 border border-gray-100 dark:border-zinc-600">
                                    <template x-if="prompt.image_url">
                                        <img :src="prompt.image_url" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!prompt.image_url">
                                        <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-zinc-600">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    </template>
                                </div>
                                
                                <div class="flex-grow min-w-0">
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 x-text="prompt.name || 'Untitled Prompt'" class="font-semibold text-gray-900 dark:text-white truncate pr-2 text-sm"></h4>
                                        <span class="flex-shrink-0 px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 dark:bg-zinc-700 dark:text-gray-300" x-text="prompt.category || 'General'"></span>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-3 mb-2 font-mono leading-relaxed" x-text="prompt.prompt"></p>
                                    <div class="flex items-center justify-between text-xs text-gray-400">
                                        <span x-text="new Date(prompt.created_at).toLocaleDateString()"></span>
                                        <span class="text-purple-600 font-medium group-hover:underline">Select &rarr;</span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <!-- Simple Pagination (Load More) -->
                    <div x-show="libraryNextPageUrl" class="mt-6 text-center">
                         <button @click="loadMorePrompts" 
                                 class="px-4 py-2 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300 transition-colors">
                            Load More
                         </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    @push('scripts')
    <script>
    function productsVirtualApp() {
        return {
            // State
            inputMode: 'upload', // 'upload' or 'prompt'
            selectedPrompt: null,
            modelImage: null,
            modelImagePreview: null,
            productImages: [],
            productPreviews: [],
            prompt: '',
            refinedPrompt: '',
            promptStatus: 'waiting',
            jobId: null,
            resultImage: null,
            error: null,
            isAnalyzing: false,
            isGenerating: false,
            
            // Library State
            showLibraryModal: false,
            libraryPrompts: [],
            libraryLoading: false,
            libraryNextPageUrl: null,
            
            // Dev Mode State
            isDevMode: {{ $isDevMode ? 'true' : 'false' }},
            showDebugModal: false,
            showDebugButton: false,
            debugInfo: null,
            
            quota: {
                daily: {{ $userQuota->getRemainingDailyQuota() }},
                total: {{ $userQuota->getRemainingTotalQuota() }}
            },
            
            params: {
                sizeRatio: '1:1',
                background: 'auto',
                quality: 'low',
                format: 'png',
                numImages: 1
            },

            // Computed
            get canAnalyze() {
                if (this.inputMode === 'upload') {
                    return this.modelImage && this.productImages.length > 0;
                } else {
                    return this.selectedPrompt && this.productImages.length > 0;
                }
            },
            
            get canGenerate() {
                return this.jobId && this.prompt && (this.quota.daily > 0 || this.quota.daily === -1);
            },

            // Methods
            handleModelUpload(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                if (file.size > 2 * 1024 * 1024) {
                    this.error = 'Image must be less than 2MB';
                    return;
                }
                
                this.modelImage = file;
                this.modelImagePreview = URL.createObjectURL(file);
                this.error = null;
            },
            
            removeModelImage() {
                this.modelImage = null;
                this.modelImagePreview = null;
                this.resetState();
            },
            
            handleProductUpload(e) {
                const files = Array.from(e.target.files);
                const remaining = 4 - this.productImages.length;
                const toAdd = files.slice(0, remaining);
                
                for (const file of toAdd) {
                    if (file.size > 2 * 1024 * 1024) {
                        this.error = 'Each image must be less than 2MB';
                        continue;
                    }
                    this.productImages.push(file);
                    this.productPreviews.push(URL.createObjectURL(file));
                }
                this.error = null;
            },
            
            removeProductImage(index) {
                this.productImages.splice(index, 1);
                this.productPreviews.splice(index, 1);
                if (this.productImages.length === 0) {
                    this.resetState();
                }
            },
            
            resetState() {
                this.prompt = '';
                this.refinedPrompt = '';
                this.promptStatus = 'waiting';
                this.jobId = null;
                this.resultImage = null;
            },
            
            async analyzeImages() {
                if (!this.canAnalyze) return;
                
                this.isAnalyzing = true;
                this.error = null;
                this.promptStatus = 'processing';
                
                const formData = new FormData();
                
                if (this.inputMode === 'upload') {
                    formData.append('model_image', this.modelImage);
                } else {
                    formData.append('prompt_id', this.selectedPrompt.id);
                }
                
                this.productImages.forEach((img, i) => {
                    formData.append('product_images[]', img);
                });
                
                try {
                    const response = await fetch('{{ route("features.products-virtual.analyze") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.jobId = data.job_id;
                        this.prompt = data.prompt;
                        this.refinedPrompt = data.prompt;
                        this.promptStatus = 'completed';
                        
                        // Handle dev mode debug info
                        if (data.dev_mode && data.debug_info) {
                            this.debugInfo = data.debug_info;
                            this.showDebugButton = true;
                        }
                    } else {
                        this.error = data.error || 'Analysis failed';
                        this.promptStatus = 'waiting';
                    }
                } catch (err) {
                    this.error = 'Network error. Please try again.';
                    this.promptStatus = 'waiting';
                } finally {
                    this.isAnalyzing = false;
                }
            },
            
            async generateImage() {
                if (!this.canGenerate) return;
                
                this.isGenerating = true;
                this.error = null;
                
                try {
                    const response = await fetch('{{ route("features.products-virtual.generate") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            job_id: this.jobId,
                            prompt: this.refinedPrompt || this.prompt,
                            size_ratio: this.params.sizeRatio,
                            background: this.params.background,
                            quality: this.params.quality,
                            format: this.params.format,
                            num_images: this.params.numImages, // Send number of images
                            model_preset_id: this.selectedModelPresetId
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.resultImage = data.result_url;
                        this.quota.daily = data.remaining_daily;
                        this.quota.total = data.remaining_total;
                        
                        // Handle dev mode debug info
                        if (data.dev_mode && data.debug_info) {
                            this.debugInfo = data.debug_info;
                            this.showDebugButton = true;
                            // Auto show modal in dev mode
                            this.showDebugModal = true;
                        }
                    } else {
                        this.error = data.error || 'Generation failed';
                    }
                } catch (err) {
                    this.error = 'Network error. Please try again.';
                } finally {
                    this.isGenerating = false;
                }
            },
            
            async openLibrary() {
                this.showLibraryModal = true;
                if (this.libraryPrompts.length === 0) {
                    await this.fetchPrompts();
                }
            },
            
            async fetchPrompts(url = '{{ route("storage.prompts.index") }}') {
                this.libraryLoading = true;
                try {
                    const response = await fetch(url + (url.includes('?') ? '&' : '?') + 'format=json', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await response.json();
                    
                    if (data.prompts && data.prompts.data) {
                        if (url.includes('page=1') || url === '{{ route("storage.prompts.index") }}') {
                             this.libraryPrompts = data.prompts.data;
                        } else {
                             this.libraryPrompts = [...this.libraryPrompts, ...data.prompts.data];
                        }
                        this.libraryNextPageUrl = data.prompts.next_page_url;
                    }
                } catch (e) {
                    console.error('Failed to load prompts', e);
                    this.error = 'Failed to load library prompts';
                } finally {
                    this.libraryLoading = false;
                }
            },
            
            async loadMorePrompts() {
                if (this.libraryNextPageUrl) {
                    await this.fetchPrompts(this.libraryNextPageUrl);
                }
            },
            
            selectPrompt(selectedPrompt) {
                this.selectedPrompt = selectedPrompt;
                this.showLibraryModal = false;
            },
            
            regenerate() {
                this.resultImage = null;
                this.generateImage();
            },
            
            async downloadResult() {
                if (!this.jobId) return;
                window.open(`/features/products-virtual/${this.jobId}/download`, '_blank');
            },
            
            async saveToLibrary() {
                if (!this.jobId) return;
                
                try {
                    const response = await fetch(`/features/products-virtual/${this.jobId}/save-to-library`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('Image saved to library successfully!');
                    } else {
                        this.error = data.error || 'Failed to save';
                    }
                } catch (err) {
                    this.error = 'Network error. Please try again.';
                }
            },
            
            // Copy debug info to clipboard
            copyDebugInfo() {
                const text = JSON.stringify(this.debugInfo, null, 2);
                navigator.clipboard.writeText(text).then(() => {
                    alert('Debug info copied to clipboard!');
                }).catch(err => {
                    console.error('Failed to copy:', err);
                });
            }
        }
    }
    </script>
    @endpush
</x-app-layout>

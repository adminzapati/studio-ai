<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
            {{ __('User Guide & Pro Tips') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">
            
            <!-- Welcome Section -->
            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-zinc-800 p-8">
                <div class="max-w-3xl">
                    <span class="inline-block py-1 px-3 rounded bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-bold uppercase tracking-widest mb-4">Beginner's Guide</span>
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">Mastering Studio AI</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-lg leading-relaxed">
                        Welcome to Studio AI! This guide is designed to help you create stunning product photography even if you have no prior experience with AI. Learn the secrets of professional prompting and how to use our tools effectively.
                    </p>
                </div>
            </div>

            <!-- Virtual Try-On Guide -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-6">
                    <div class="flex items-center mb-6">
                         <div class="p-3 rounded-xl bg-fuchsia-50 dark:bg-fuchsia-900/20 text-fuchsia-600 dark:text-fuchsia-400 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">Virtual Try-On Workflow</h4>
                    </div>
                    
                    <ol class="relative border-l border-gray-200 dark:border-zinc-700 ml-3 space-y-8">                  
                        <li class="mb-10 ml-6">            
                            <span class="absolute flex items-center justify-center w-6 h-6 bg-fuchsia-100 rounded-full -left-3 ring-8 ring-white dark:ring-zinc-900 dark:bg-fuchsia-900">
                                <span class="text-xs font-bold text-fuchsia-600 dark:text-fuchsia-300">1</span>
                            </span>
                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">Upload Images</h3>
                            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Upload your <b>Model Image</b> (the person or scene) and your <b>Product Image</b> (the clothing/item). Use high-quality JPG/PNG files.</p>
                        </li>
                        <li class="mb-10 ml-6">
                            <span class="absolute flex items-center justify-center w-6 h-6 bg-fuchsia-100 rounded-full -left-3 ring-8 ring-white dark:ring-zinc-900 dark:bg-fuchsia-900">
                                <span class="text-xs font-bold text-fuchsia-600 dark:text-fuchsia-300">2</span>
                            </span>
                            <h3 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">AI Analysis & Prompting</h3>
                            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Click <b>"Start Analysis"</b>. Our Gemini AI will scan your images and automatically write a detailed prompt describing the model, pose, and product.</p>
                        </li>
                        <li class="mb-10 ml-6">
                            <span class="absolute flex items-center justify-center w-6 h-6 bg-fuchsia-100 rounded-full -left-3 ring-8 ring-white dark:ring-zinc-900 dark:bg-fuchsia-900">
                                <span class="text-xs font-bold text-fuchsia-600 dark:text-fuchsia-300">3</span>
                            </span>
                            <h3 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">Generate & Download</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Review the prompt, choose your settings (High Quality recommended), and click <b>Generate</b>. Save the result to your library!</p>
                        </li>
                    </ol>
                </div>

                <!-- Prompt Engineering Tips -->
                <div class="space-y-6">
                    <div class="bg-gradient-to-br from-indigo-600 to-violet-600 rounded-2xl shadow-lg p-8 text-white">
                        <h4 class="text-2xl font-bold mb-4">Prompting Secret Sauce 🤫</h4>
                        <p class="text-indigo-100 mb-6">A great prompt is like a recipe. You need the right ingredients in the right order.</p>
                        
                        <div class="space-y-4">
                            <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                                <span class="block text-xs font-bold text-indigo-200 uppercase mb-1">Formula</span>
                                <p class="font-mono text-sm">"[Subject] + [Environment] + [Lighting] + [Camera Angle] + [Style]"</p>
                            </div>
                            
                            <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                                <span class="block text-xs font-bold text-indigo-200 uppercase mb-1">Example</span>
                                <p class="text-sm italic">"Fashion shot of a model wearing a silk dress, standing in a <b>modern minimalist living room</b>, with <b>soft golden hour sunlight</b> coming from the window, shot on <b>35mm lens</b>, photorealistic, 8k."</p>
                            </div>
                        </div>
                    </div>

                    <!-- Lighting Cheat Sheet -->
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-6">
                        <h4 class="font-bold text-gray-900 dark:text-white mb-4">Lighting Cheat Sheet</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-gray-50 dark:bg-zinc-800 rounded-xl">
                                <p class="font-bold text-gray-900 dark:text-white text-sm">Rembrandt Lighting</p>
                                <p class="text-xs text-gray-500 mt-1">Dramatic, moody, triangle of light on cheek.</p>
                            </div>
                            <div class="p-3 bg-gray-50 dark:bg-zinc-800 rounded-xl">
                                <p class="font-bold text-gray-900 dark:text-white text-sm">Golden Hour</p>
                                <p class="text-xs text-gray-500 mt-1">Warm, soft, glowing sunlight (Sunset/Sunrise).</p>
                            </div>
                            <div class="p-3 bg-gray-50 dark:bg-zinc-800 rounded-xl">
                                <p class="font-bold text-gray-900 dark:text-white text-sm">Softbox / Studio</p>
                                <p class="text-xs text-gray-500 mt-1">Clean, even, professional e-commerce look.</p>
                            </div>
                            <div class="p-3 bg-gray-50 dark:bg-zinc-800 rounded-xl">
                                <p class="font-bold text-gray-900 dark:text-white text-sm">Cinematic</p>
                                <p class="text-xs text-gray-500 mt-1">High contrast, teal & orange tones, movie-like.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pro Tips Collection -->
             <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-8">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Expert Tips for Better Results</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Tip 1 -->
                    <div class="space-y-2">
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold">1</div>
                        <h5 class="font-bold text-gray-900 dark:text-white">Be Specific with Fabric</h5>
                        <p class="text-sm text-gray-500">Instead of just "dress", say "satin silk dress" or "knitted wool sweater". The AI knows materials very well.</p>
                    </div>
                    <!-- Tip 2 -->
                    <div class="space-y-2">
                         <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400 font-bold">2</div>
                        <h5 class="font-bold text-gray-900 dark:text-white">Use "Negative Prompts"</h5>
                        <p class="text-sm text-gray-500">If the image looks blurry, try adding "blurry, low quality, distorted" to the negative prompt (if available) or imply high quality in your main prompt.</p>
                    </div>
                    <!-- Tip 3 -->
                    <div class="space-y-2">
                         <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400 font-bold">3</div>
                        <h5 class="font-bold text-gray-900 dark:text-white">The "Generic" Rule</h5>
                        <p class="text-sm text-gray-500">For virtual try-on, avoid describing the specific product color (e.g., "red shirt"). Just say "shirt". Let the uploaded image dictate the color.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

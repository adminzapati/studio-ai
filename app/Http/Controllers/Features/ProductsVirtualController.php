<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Data\Api\FalAiClient;
use App\Data\Api\GeminiClient;
use App\Models\ImageLibrary;
use App\Models\ModelPreset;
use App\Models\ProductsVirtualJob;
use App\Models\UserQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use App\Services\ActivityLogger;
use App\Services\CreditService;

/**
 * Products Virtual Controller
 * 
 * Handles the Products Virtual feature workflow:
 * 1. Upload model/scene image
 * 2. Upload product images (max 4)
 * 3. Analyze with Gemini AI
 * 4. Generate with Fal.ai GPT Image 1
 * 5. Save or download result
 */
class ProductsVirtualController extends Controller
{
    protected FalAiClient $falClient;
    protected GeminiClient $geminiClient;

    public function __construct()
    {
        $this->falClient = new FalAiClient();
        $this->geminiClient = new GeminiClient();
    }

    /**
     * Check if dev mode is enabled.
     */
    protected function isDevMode(): bool
    {
        return Setting::get('products_virtual_dev_mode', 'false') === 'true';
    }

    /**
     * Display the Products Virtual page.
     */
    public function index()
    {
        $modelPresets = ModelPreset::where('is_active', true)->get();
        $userQuota = UserQuota::getOrCreateForUser(auth()->id());
        
        // Get recent user library images for quick selection
        $recentImages = auth()->user()->images()
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get();

        $isDevMode = $this->isDevMode();
        
        $subscription = \App\Models\UserSubscription::where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();
        $credits = $subscription ? $subscription->credits_remaining : 0;

        return view('features.products-virtual.index', compact(
            'modelPresets',
            'userQuota',
            'recentImages',
            'isDevMode',
            'credits'
        ));
    }

    /**
     * Analyze uploaded images with Gemini AI.
     */
    public function analyze(Request $request)
    {
        // Validation: model_image is required ONLY if prompt_id is not provided
        $request->validate([
            'prompt_id' => 'sometimes|nullable|exists:saved_prompts,id',
            'model_image' => 'required_without:prompt_id|image|max:2048', // 2MB max
            'product_images' => 'required|array|min:1|max:4',
            'product_images.*' => 'image|max:2048',
        ]);

        $devMode = $this->isDevMode();

        try {
            $debugInfo = ['dev_mode' => $devMode];
            $productImagePaths = [];
            $productImageFalUrls = [];

            foreach ($request->file('product_images') as $image) {
                // MODIFIED: Store in temp inputs folder
                $path = $image->store('temp/products-virtual/inputs/products', 'public');
                $productImagePaths[] = $path;
                
                if ($devMode) {
                    $productImageFalUrls[] = Storage::disk('public')->path($path);
                } else {
                    // SKIPPED: Using JIT Base64 for Product Images in 'generate' Step
                    // $url = $this->falClient->uploadToStorage(Storage::disk('public')->path($path));
                    // if (!$url) throw new \Exception('Failed to upload product image to Fal.ai');
                    // $productImageFalUrls[] = $url;
                    $productImageFalUrls[] = null; 
                }
            }

            // 2. Determine Mode
            if ($request->has('prompt_id') && $request->prompt_id) {
                // --- PROMPT MODE ---
                $prompt = \App\Models\SavedPrompt::findOrFail($request->prompt_id);
                
                $promptText = $prompt->prompt;
                $modelImageFalUrl = null;
                
                // If prompt has an associated image, use it as reference
                if ($prompt->image_path) {
                    // Resolve the absolute path (works for both dev and production)
                    $absolutePath = null;
                    if (Storage::disk('public')->exists($prompt->image_path)) {
                        $absolutePath = Storage::disk('public')->path($prompt->image_path);
                    } elseif (Storage::exists($prompt->image_path)) {
                        $absolutePath = Storage::path($prompt->image_path);
                    }

                    if ($absolutePath && file_exists($absolutePath)) {
                        if ($devMode) {
                            // DEV MODE: Use local path for Gemini instead of uploading to Fal
                            $modelImageFalUrl = $absolutePath;
                        } else {
                            // PRODUCTION: Upload to Fal.ai Storage
                            // SKIPPED: User requested no Model Upload
                            // $modelImageFalUrl = $this->falClient->uploadToStorage($absolutePath);
                            $modelImageFalUrl = null;
                        }
                    }
                }

                // If no model image, use first product image as reference
                if (!$modelImageFalUrl && !empty($productImageFalUrls)) {
                    $modelImageFalUrl = $productImageFalUrls[0];
                }

                $job = ProductsVirtualJob::create([
                    'user_id' => auth()->id(),
                    'model_image_path' => $prompt->image_path, // Save local path
                    'product_images_paths' => $productImagePaths, // Save local paths (CRITICAL FIX)
                    'model_image_fal_url' => null, // Skipped upload
                    'product_images_fal_urls' => [], // Skipped upload
                    'status' => 'analyzing', // Or 'prompt_ready' since no analysis needed?
                    'gemini_prompt' => $promptText,
                ]);

                // Build Fal.ai API-compatible debug structure
                $allImageUrls = [];
                if ($modelImageFalUrl) {
                    $allImageUrls[] = $modelImageFalUrl;
                }
                foreach ($productImageFalUrls as $url) {
                    $allImageUrls[] = $url;
                }

                if ($devMode) {
                    $debugInfo = [
                        'mode' => 'prompt_library',
                        'prompt_id' => $prompt->id,
                        'has_prompt_image' => (bool)$prompt->image_path,
                        'fal_api_request' => [
                            'prompt' => $promptText,
                            'image_urls' => $allImageUrls,
                            'image_size' => 'auto',
                            'background' => 'auto',
                            'quality' => 'high',
                            'input_fidelity' => 'high',
                            'num_images' => 1,
                            'output_format' => 'png',
                        ],
                        'dev_mode' => true,
                    ];
                }

                return response()->json([
                    'success' => true,
                    'job_id' => $job->id,
                    'prompt' => $job->gemini_prompt,
                    'dev_mode' => $devMode,
                    'debug_info' => $devMode ? $debugInfo : null
                ]);

            } else {
                // --- UPLOAD MODE (Normal) ---
                // MODIFIED: Store in temp inputs folder
                $modelImagePath = $request->file('model_image')->store('temp/products-virtual/inputs/models', 'public');
                $localModelPath = Storage::disk('public')->path($modelImagePath);
                
                // Store LOCAL paths relative to storage for DB
                $dbModelPath = $modelImagePath;
                
                // Get local paths for products
                $dbProductPaths = [];
                $localProductPaths = [];
                foreach ($productImagePaths as $path) {
                    $dbProductPaths[] = $path;
                    $localProductPaths[] = Storage::disk('public')->path($path);
                }

                // Call Gemini Analysis using LOCAL Target Model ONLY (Optimize Latency)
                // User Request: "Submit Upload sends target to gemini only"
                $analysisResult = $this->geminiClient->analyzeImageForProductVirtual(
                    $localModelPath, 
                    [] // Empty products list - Gemini only needs to analyze the scene/model
                );

                // Build debug info (Dev Mode)
                 if ($devMode) {
                    $debugInfo = [
                        'mode' => 'upload_reference',
                        'gemini_response' => $analysisResult,
                        'dev_mode' => true,
                    ];
                }

                $job = ProductsVirtualJob::create([
                    'user_id' => auth()->id(),
                    // Store LOCAL paths now, FAL URLs will be generated in 'generate' step
                    'model_image_path' => $dbModelPath, 
                    'product_images_paths' => $dbProductPaths,
                    'model_image_fal_url' => null, // Delayed upload
                    'product_images_fal_urls' => [], // Delayed upload
                    'status' => 'analyzing',
                    'gemini_prompt' => $analysisResult['prompt'] ?? 'Failed to generate prompt',
                ]);

                return response()->json([
                    'success' => true,
                    'job_id' => $job->id,
                    'prompt' => $job->gemini_prompt,
                    'dev_mode' => $devMode,
                    'debug_info' => $devMode ? $debugInfo : null
                ]);
            }

        } catch (\Exception $e) {
            \App\Core\Logging\AppLogger::error('Products Virtual Analysis Failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate image with Fal.ai.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:products_virtual_jobs,id',
            'prompt' => 'required|string|min:10',
            'size_ratio' => 'sometimes|string|in:1:1,2:3,3:2',
            'background' => 'sometimes|string|in:auto,white,transparent',
            'quality' => 'sometimes|string|in:low,medium,high',
            'format' => 'sometimes|string|in:png,jpg,webp',
            'model_preset_id' => 'sometimes|nullable|exists:model_presets,id',
            'num_images' => 'sometimes|integer|min:1|max:4',
        ]);

        $devMode = $this->isDevMode();

        try {
            $job = ProductsVirtualJob::where('id', $request->job_id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // Check user quota (skip in dev mode)
            if (!$devMode) {
                $userQuota = UserQuota::getOrCreateForUser(auth()->id());
                if (!$userQuota->canGenerate()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Quota exceeded. Please try again tomorrow or contact admin.'
                    ], 429);
                }
            }
            
            // --- LATER STAGE FAL.AI UPLOAD (Optimized Workflow) ---
            if (!$devMode) {
                // 1. Upload Model Image if not present
                /* SKIPPED PER USER REQUEST (Target Model is Local-Only for Gemini)
                if (empty($job->model_image_fal_url) && $job->model_image_path) {
                    $absolutePath = Storage::disk('public')->path($job->model_image_path);
                    $url = $this->falClient->uploadToStorage($absolutePath);
                    if ($url) {
                        $job->model_image_fal_url = $url;
                        $job->save(); // Save progress
                    } else {
                        throw new \Exception('Failed to upload model image to Fal.ai (Delayed Phase)');
                    }
                }
                */

                // 2. Upload Product Images if not present
                /* SKIPPED: Using JIT Base64 for Product Images to avoid Fal.media .bin 422 errors */
                /*
                if (empty($job->product_images_fal_urls) && !empty($job->product_images_paths)) {
                    $uploadedUrls = [];
                    foreach ($job->product_images_paths as $path) {
                        $absolutePath = Storage::disk('public')->path($path);
                        $url = $this->falClient->uploadToStorage($absolutePath);
                        if ($url) {
                            $uploadedUrls[] = $url;
                        }
                    }
                    if (!empty($uploadedUrls)) {
                         $job->product_images_fal_urls = $uploadedUrls;
                         $job->save(); // Save progress
                    }
                }
                */
            } else {
                 // Dev Mode: Mock URLs with local paths if missing
                 if (empty($job->model_image_fal_url) && $job->model_image_path) {
                     $job->model_image_fal_url = Storage::disk('public')->path($job->model_image_path);
                 }
                 if (empty($job->product_images_fal_urls) && !empty($job->product_images_paths)) {
                     $urls = [];
                     foreach ($job->product_images_paths as $path) {
                         $urls[] = Storage::disk('public')->path($path);
                     }
                     $job->product_images_fal_urls = $urls;
                 }
                 $job->save();
            }

            // Enforce num_images limit based on role
            $numImages = $request->input('num_images', 1);
            if (!auth()->user()->hasAnyRole(['Admin', 'Manager'])) {
                $numImages = 1;
            }

            // Update job with refined prompt and parameters
            $job->update([
                'refined_prompt' => $request->prompt,
                'size_ratio' => $request->size_ratio ?? '1:1',
                'background' => $request->background ?? 'auto',
                'quality' => $request->quality ?? 'low',
                'format' => $request->input('format', 'png'),
                'model_preset_id' => $request->model_preset_id,
                'status' => 'generating',
            ]);

            // Map size_ratio to GPT-Image 1.5 image_size
            $imageSizeMap = [
                '1:1' => '1024x1024',
                '3:2' => '1536x1024',
                '2:3' => '1024x1536',
            ];
            $imageSize = $imageSizeMap[$job->size_ratio] ?? '1024x1024'; // Fallback

            // Build image_urls list (List of strings)
            $falImageUrls = [];
            
            // Add Model Image
            // SKIPPED: User requested only Product Image to be sent to Fal.ai
            /*
            if ($job->model_image_fal_url) {
                $falImageUrls[] = $job->model_image_fal_url;
            }
            */
            
            // Add Product Images
            // MODIFIED: Use Local Paths converted to Base64 (JIT) instead of previously uploaded URLs
            // This works around Fal.media storage issues.
            /*
             if (!empty($job->product_images_fal_urls) && is_array($job->product_images_fal_urls)) {
                foreach ($job->product_images_fal_urls as $url) {
                    $falImageUrls[] = $url;
                }
            }
            */
            if (!empty($job->product_images_paths)) {
                foreach ($job->product_images_paths as $path) {
                    try {
                        $absPath = Storage::disk('public')->path($path);
                        if (file_exists($absPath)) {
                            $fileData = file_get_contents($absPath);
                            $mimeType = mime_content_type($absPath) ?: 'image/png';
                            $base64 = base64_encode($fileData);
                            $dataUri = "data:{$mimeType};base64,{$base64}";
                            $falImageUrls[] = $dataUri;
                            \App\Core\Logging\AppLogger::info('Converted Product Image to Base64', ['path' => $path, 'size' => strlen($base64)]);
                        }
                    } catch (\Exception $e) {
                         \App\Core\Logging\AppLogger::error('Failed to convert image to Base64', ['path' => $path, 'error' => $e->getMessage()]);
                    }
                }
            }


            // Build User-Friendly Debug URLs (for Dev Mode display only)
            // In Dev Mode, these are local paths, so we might want to keep them as strings or objects for clarity?
            // The user requested checking the JSON sent to Fal.ai. Fal.ai expects strings.
            // So we will keep them as strings.

            // Build Fal.ai API request body
            $falApiRequest = [
                'prompt' => $request->prompt,
                'image_urls' => $falImageUrls,
                'image_size' => $imageSize,
                'background' => $job->background,
                'quality' => $job->quality,
                'input_fidelity' => 'high',
                'num_images' => (int)$numImages, // Cast to int explicitly
                'output_format' => $job->format,
            ];

            // Build debug info
            $debugInfo = [
                'dev_mode' => $devMode,
                'timestamp' => now()->toISOString(),
                'job_id' => $job->id,
                'fal_api_request' => $falApiRequest,
            ];

            if ($devMode) {
                // DEV MODE: Skip Fal.ai API, use model image as mock result
                $debugInfo['fal_api_skipped'] = true;
                $debugInfo['would_call_endpoint'] = 'POST https://fal.run/fal-ai/gpt-image-1.5/edit';
                $debugInfo['quota_deducted'] = false;

                // Use model image as mock result
                $resultUrl = $job->model_image_fal_url;

                // Update job with mock result
                $job->update([
                    'status' => 'completed',
                    'result_image_path' => 'DEV_MODE_MOCK',
                ]);

                $userQuota = UserQuota::getOrCreateForUser(auth()->id());

                return response()->json([
                    'success' => true,
                    'job_id' => $job->id,
                    'result_url' => $resultUrl,
                    'remaining_daily' => $userQuota->getRemainingDailyQuota(),
                    'remaining_total' => $userQuota->getRemainingTotalQuota(),
                    'dev_mode' => true,
                    'debug_info' => $debugInfo,
                ]);
            }

            // PRODUCTION MODE: Call Fal.ai Edit Image API
            $result = $this->falClient->editImage(
                $request->prompt,
                $falImageUrls,
                [
                    'image_size' => $imageSize,
                    'background' => $job->background,
                    'quality' => $job->quality,
                    'input_fidelity' => 'high',
                    'num_images' => (int)$numImages,
                    'format' => $job->format,
                ]
            );

            if (!$result || empty($result['images'])) {
                $job->update([
                    'status' => 'failed',
                    'error_message' => 'Fal.ai generation failed'
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Image generation failed'
                ], 500);
            }

            // Download and store result image
            // MODIFIED: Store in HISTORY folder (Permanent until deleted/saved)
            $resultImageUrl = $result['images'][0]['url'];
            $resultContent = file_get_contents($resultImageUrl);
            
            // Generate filename
            $filename = 'result_' . $job->id . '_' . time() . '.' . $job->format;
            $historyPath = 'history/products-virtual/' . $filename;
            
            // Ensure directory
            if (!Storage::disk('public')->exists('history/products-virtual')) {
                Storage::disk('public')->makeDirectory('history/products-virtual');
            }
            
            Storage::disk('public')->put($historyPath, $resultContent);

            // Update job with result
            $job->update([
                'status' => 'completed',
                'result_image_path' => $historyPath,
            ]);

            // Deduct Credits
             if ($devMode) {
                 // No deduction in dev mode
             } else {
                 $cost = CreditService::calculateCost($job->quality, $job->size_ratio);
                 CreditService::deductCredits(
                     auth()->id(), 
                     $cost, 
                     "Generated virtual product image ({$job->quality}, {$job->size_ratio})",
                     ['job_id' => $job->id]
                 );
             }

            // Log activity
            ActivityLogger::logProductsVirtualGeneration(
                $job->id,
                $request->prompt,
                $historyPath,
                [
                    'size_ratio' => $job->size_ratio,
                    'quality' => $job->quality,
                    'background' => $job->background,
                    'format' => $job->format,
                ]
            );

            // Get updated remaining credits
            $subscription = \App\Models\UserSubscription::where('user_id', auth()->id())->first();
            $remaining = $subscription ? $subscription->credits_remaining : 0;

            return response()->json([
                'success' => true,
                'job_id' => $job->id,
                'result_url' => Storage::disk('public')->url($historyPath),
                'remaining_credits' => $remaining,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get job status for polling.
     */
    public function status($id)
    {
        $job = ProductsVirtualJob::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->json([
            'status' => $job->status,
            'result_url' => $job->result_image_path 
                ? Storage::disk('public')->url($job->result_image_path) 
                : null,
            'error' => $job->error_message,
        ]);
    }

    /**
     * Save result to Image Library.
     */
    public function saveToLibrary(Request $request, $id)
    {
        $job = ProductsVirtualJob::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->firstOrFail();

        if (!$job->result_image_path) {
            return response()->json([
                'success' => false,
                'error' => 'No result image to save'
            ], 400);
        }

        // Move from temp to permanent location
        $newPath = 'images/products-virtual/' . auth()->id() . '/' . basename($job->result_image_path);
        
        // Ensure directory exists
        if (!Storage::disk('public')->exists(dirname($newPath))) {
            Storage::disk('public')->makeDirectory(dirname($newPath));
        }

        try {
            Storage::disk('public')->copy($job->result_image_path, $newPath);
        } catch (\Exception $e) {
            \App\Core\Logging\AppLogger::error('Failed to move image to library', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to save image: ' . $e->getMessage()
            ], 500);
        }

        // Create image library entry
        $image = ImageLibrary::create([
            'user_id' => auth()->id(),
            'path' => $newPath,
            'type' => 'products_virtual',
            'tags' => [
                'source' => 'products_virtual',
                'file_name' => basename($newPath),
                'format' => $job->format,
                'size' => Storage::disk('public')->size($newPath)
            ],
        ]);

        // Update job with new path
        $job->update(['result_image_path' => $newPath]);

        return response()->json([
            'success' => true,
            'image_id' => $image->id,
            'message' => 'Image saved to library successfully'
        ]);
    }

    /**
     * Download result image.
     */
    public function download($id)
    {
        $job = ProductsVirtualJob::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->firstOrFail();

        if (!$job->result_image_path || !Storage::disk('public')->exists($job->result_image_path)) {
            abort(404, 'Result image not found');
        }

        return Storage::disk('public')->download(
            $job->result_image_path,
            'products-virtual-' . $job->id . '.' . $job->format
        );
    }
}

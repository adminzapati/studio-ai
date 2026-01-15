<?php

namespace App\Data\Api;

use Illuminate\Support\Facades\Http;
use App\Core\Logging\AppLogger;

/**
 * Fal.ai API Client
 * 
 * Client for Fal.ai image generation API.
 * Part of the Data layer in Clean Architecture.
 */
class FalAiClient
{
    private string $apiKey;
    private string $baseUrl = 'https://fal.run';

    public function __construct()
    {
        $this->apiKey = \App\Models\Setting::get('fal_api_key', config('services.fal.api_key', ''));
    }

    /**
     * Upload image to Fal.ai Storage
     * 
     * Strategy:
     * 1. Try Fal.media generic upload (observed 200 OK in logs)
     * 2. Fallback to older upload endpoints
     * 3. CRITICAL FALLBACK: Use Base64 Data URI if file size allows (< 10MB)
     * 
     * @param string $filePath Absolute path to local file
     * @return string|null Public URL of uploaded file
     */
    public function uploadToStorage(string $filePath): ?string
    {
        AppLogger::aiCall('FalAi', 'uploadToStorage', ['file' => basename($filePath)]);

        $fileName = basename($filePath);
        $fileContents = file_get_contents($filePath);
        $fileSize = strlen($fileContents);
        $contentType = mime_content_type($filePath) ?: 'image/png';
        
        AppLogger::info('FalAi Upload details', [
            'file' => $fileName,
            'mime' => $contentType, 
            'size' => $fileSize
        ]);

        // Endpoints to try (Prioritize standard global endpoint)
        $endpoints = [
            'https://fal.media/files/upload',          // Standard
            // 'https://fal.media/files/kangaroo/upload', // Removed: suspected of enforcing .bin extension
        ];

        foreach ($endpoints as $endpoint) {
            try {
                // Fal.media sometimes uses different auth or no auth for temp files
                // We'll try with standard Fal auth first
                $response = Http::timeout(60)
                    ->withHeaders([
                        'Authorization' => "Key {$this->apiKey}",
                        'Accept' => 'application/json',
                    ])
                    ->attach('file', $fileContents, $fileName, ['Content-Type' => $contentType])
                    ->post($endpoint);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Log partial success for debugging
                    AppLogger::info('FalAi partial upload response', ['body' => $data]);

                    // Map known response fields
                    $fileUrl = $data['access_url'] ?? $data['file_url'] ?? $data['url'] ?? null;
                    
                    if ($fileUrl) {
                        return $fileUrl;
                    }
                }
            } catch (\Exception $e) {
                // Ignore and try next
            }
        }

        // --- FALLBACK: BASE64 DATA URI ---
        // REMOVED: Base64 fallback caused MySQL "server gone away" errors.
        // Direct upload is now working correctly via generic endpoints.
        
        AppLogger::error('FalAi Storage Upload failed after all attempts');
        return null;
    }

    /**
     * Edit image using GPT Image 1.5
     * 
     * @param string $prompt The edit instruction
     * @param array $imageUrls Array of public URLs (List of strings)
     * @param array $options Additional options (image_size, quality, format, etc.)
     */
    public function editImage(string $prompt, array $imageUrls, array $options = []): ?array
    {
        AppLogger::aiCall('FalAi', 'editImage', ['prompt' => $prompt]);
        
        try {
            $response = Http::timeout(120)->withHeaders([
                'Authorization' => "Key {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/fal-ai/gpt-image-1.5/edit", [
                'prompt' => $prompt,
                'image_urls' => $imageUrls,
                'image_size' => $options['image_size'] ?? 'auto',
                'background' => $options['background'] ?? 'auto',
                'quality' => $options['quality'] ?? 'high',
                'input_fidelity' => $options['input_fidelity'] ?? 'high',
                'num_images' => $options['num_images'] ?? 1,
                'output_format' => $options['format'] ?? 'png',
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            AppLogger::error('FalAi EditImage error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return null;
        } catch (\Exception $e) {
            AppLogger::error('FalAi EditImage exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Generate an image using Fal.ai
     */
    public function generateImage(string $prompt, array $options = []): ?array
    {
        AppLogger::aiCall('FalAi', 'generateImage', ['prompt' => $prompt]);

        $model = $options['model'] ?? 'fal-ai/flux/schnell';

        try {
            $response = Http::withHeaders([
                'Authorization' => "Key {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/{$model}", [
                'prompt' => $prompt,
                'image_size' => $options['image_size'] ?? 'landscape_4_3',
                'num_inference_steps' => $options['steps'] ?? 4,
                'num_images' => $options['num_images'] ?? 1,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            AppLogger::error('FalAi API error', ['status' => $response->status()]);
            return null;
        } catch (\Exception $e) {
            AppLogger::error('FalAi API exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Virtual try-on using Fal.ai
     */
    public function virtualTryOn(string $personImageUrl, string $garmentImageUrl): ?array
    {
        AppLogger::aiCall('FalAi', 'virtualTryOn');

        try {
            $response = Http::withHeaders([
                'Authorization' => "Key {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/fal-ai/cat-vton", [
                'human_image_url' => $personImageUrl,
                'garment_image_url' => $garmentImageUrl,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            AppLogger::error('FalAi VirtualTryOn exception', ['message' => $e->getMessage()]);
            return null;
        }
    }
}

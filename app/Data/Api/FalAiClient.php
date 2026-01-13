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
        $this->apiKey = config('services.fal.api_key', '');
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

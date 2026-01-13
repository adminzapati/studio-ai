<?php

namespace App\Data\Api;

use Illuminate\Support\Facades\Http;
use App\Core\Logging\AppLogger;

/**
 * Gemini API Client
 * 
 * Client for Google Gemini API integration.
 * Part of the Data layer in Clean Architecture.
 */
class GeminiClient
{
    private string $apiKey;
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta';

    public function __construct()
    {
        $this->apiKey = \App\Models\Setting::get('gemini_api_key', config('services.gemini.api_key', ''));
    }

    /**
     * Analyze an image using Gemini Vision
     */
    public function analyzeImage(string $imageBase64, string $prompt): ?array
    {
        AppLogger::aiCall('Gemini', 'analyzeImage', ['prompt' => $prompt]);

        try {
            $response = Http::post("{$this->baseUrl}/models/gemini-2.0-flash:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inline_data' => [
                                    'mime_type' => 'image/jpeg',
                                    'data' => $imageBase64,
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            AppLogger::error('Gemini API error', ['status' => $response->status()]);
            return null;
        } catch (\Exception $e) {
            AppLogger::error('Gemini API exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Generate text using Gemini
     */
    public function generateText(string $prompt): ?string
    {
        AppLogger::aiCall('Gemini', 'generateText', ['prompt' => $prompt]);

        try {
            $response = Http::post("{$this->baseUrl}/models/gemini-2.0-flash:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }

            return null;
        } catch (\Exception $e) {
            AppLogger::error('Gemini API exception', ['message' => $e->getMessage()]);
            return null;
        }
    }
}

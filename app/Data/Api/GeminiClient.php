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

    /**
     * Analyze images for Products Virtual feature
     * 
     * @param string $modelImageUrl URL of the model/scene image
     * @param array $productImageUrls URLs of product images
     * @return array|null Analysis result with prompt and has_person flag
     */
    public function analyzeImageForProductVirtual(string $modelImageUrl, array $productImageUrls): ?array
    {
        AppLogger::aiCall('Gemini', 'analyzeImageForProductVirtual', [
            'modelImage' => $modelImageUrl,
            'productCount' => count($productImageUrls)
        ]);

        $systemPrompt = <<<PROMPT
You are a professional fashion e-commerce prompt engineer and art director. Analyze the provided images and create an highly detailed, artistically accurate AI generation prompt for Virtual Try-On/Product Staging.

**INPUT IMAGES:**
- FIRST IMAGE: The Target Model/Scene (where the product will be placed).
- REMAINING IMAGES: The Product(s) to be placed.

**OBJECTIVE:**
Create a prompt that instructs the AI to RECREATE the Target Scene EXACTLY (lighting, angle, mood, props) but with the new Product seamlessly integrated.

**STEP 1: DETECT SCENE TYPE**
- **Type A: Model Shot** (Accessory/Clothing worn by a person).
- **Type B: Product Only** (Still life, product placed on surface).

**STEP 2: DETAILED SCENE ANALYSIS (CRITICAL)**
- **Camera Angle/Framing**: Close-up, Low angle, Eye-level, Wide shot.
- **Lighting**: Natural diffused, Softbox, Dappled sunlight, Warm tones, Hard shadows.
- **Micro-Details**: Texture of pavement/rug, background objects (bokeh, glass, furniture), held props.
- **Aspect Ratio**: Estimate the aspect ratio of the Target Scene (e.g., 2:3, 1:1, 16:9).

**STEP 3: GENERATE COMPOSITE PROMPT**
Choose the correct template based on Scene Type:

**TEMPLATE A (WITH PERSON/MODEL):**
"Fashion product photography, [Model Description] wearing [Generic Product], [Pose/Action], [Detailed Environment & Props], [Lighting & Atmosphere], [Camera Angle], [Quality Keywords] --ar [Aspect Ratio]"

**TEMPLATE B (PRODUCT ONLY/NO PERSON):**
"Product photography, [Generic Product], [Placement], [Detailed Environment & Props], [Lighting & Atmosphere], [Camera Angle], [Quality Keywords] --ar [Aspect Ratio]"

**EXAMPLES TO FOLLOW:**
- *Model Shot Example*: "Fashion product photography, male model wearing sandals, seated with legs extended, on grey brick pavement with blurred urban background featuring a glass prop, natural diffused lighting with soft shadows, eye-level angle, 8K photorealistic, commercial quality --ar 2:3"
- *Product Only Example*: "Product photography, sandals, placed on a fluffy white rug. Background includes blurred white cubic forms. Soft, diffused lighting with subtle shadows. Eye-level angle, 8K photorealistic, studio product shot --ar 2:3"

**STRICT RULES:**
1. **BE SPECIFIC**: Do not say "outside". Say "grey brick pavement". Do not say "lighting". Say "dappled natural sunlight".
2. **USE GENERIC PRODUCT TERMS**: "sandals", "dress", "t-shirt". NO color/material descriptions of the product itself.
3. **INCLUDE PROPS**: If there is a glass of iced coffee, MENTION IT.
4. **COLOR FIDELITY**: IMPORTANT! Add the phrase "maintaining true-to-life product color" to the prompt to prevent lighting (e.g., golden hour) from altering the product's actual color.

**Output Format (JSON):**
{
    "analysis": "Brief analysis of scene type, lighting, and key elements.",
    "has_person": true/false,
    "prompt": "The generated prompt string following the templates above.",
    "suggested_aspect_ratio": "2:3" // or 1:1, 16:9 based on target image
}

Respond ONLY with the RAW JSON object. Do not use Markdown code blocks.
PROMPT;

        try {
            // Helper to convert image to Gemini Part
            $imageToPart = function($pathOrUrl) {
                try {
                    $imageData = null;
                    
                    // CRITICAL: Check if it's a URL FIRST before any normalization.
                    // DO NOT use DIRECTORY_SEPARATOR on URLs (breaks them on Windows).
                    $isUrl = filter_var($pathOrUrl, FILTER_VALIDATE_URL) || 
                             str_starts_with($pathOrUrl, 'https://') || 
                             str_starts_with($pathOrUrl, 'http://');
                    
                    AppLogger::info("Gemini loading image: " . substr($pathOrUrl, 0, 80) . "...");
                    
                    if ($isUrl) {
                        // Fetch URL content via HTTP
                        $response = Http::timeout(30)->get($pathOrUrl);
                        if ($response->successful()) {
                            $imageData = $response->body();
                        }
                    } else {
                        // Local file: normalize path for Windows
                        $normalizedPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $pathOrUrl);
                        if (file_exists($normalizedPath)) {
                            $imageData = file_get_contents($normalizedPath);
                        }
                    }
                    
                    if ($imageData) {
                        // Detect actual MIME type from image data
                        $finfo = new \finfo(FILEINFO_MIME_TYPE);
                        $mimeType = $finfo->buffer($imageData) ?: 'image/jpeg';
                        
                        // Ensure it's an image MIME type (not application/octet-stream)
                        if (str_starts_with($mimeType, 'application/') || $mimeType === 'application/octet-stream') {
                            // Try to infer from common image signatures
                            $header = substr($imageData, 0, 8);
                            if (str_starts_with($header, "\xFF\xD8\xFF")) {
                                $mimeType = 'image/jpeg';
                            } elseif (str_starts_with($header, "\x89PNG")) {
                                $mimeType = 'image/png';
                            } elseif (str_starts_with($header, "GIF")) {
                                $mimeType = 'image/gif';
                            } elseif (str_starts_with($header, "RIFF") && substr($imageData, 8, 4) === "WEBP") {
                                $mimeType = 'image/webp';
                            } else {
                                $mimeType = 'image/jpeg'; // Default fallback
                            }
                        }
                        
                        AppLogger::info("Gemini image loaded successfully, size: " . strlen($imageData) . ", mime: " . $mimeType);
                        return [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => base64_encode($imageData)
                            ]
                        ];
                    } else {
                        AppLogger::error("Gemini failed to load image data for: " . $pathOrUrl);
                    }
                } catch (\Exception $e) {
                    AppLogger::error("Gemini image load error: " . $e->getMessage());
                }
                return null;
            };

            // Build parts array
            $parts = [['text' => $systemPrompt]];
            
            // Add model image
            if ($part = $imageToPart($modelImageUrl)) {
                $parts[] = $part;
            }
            
            // Add product images
            foreach ($productImageUrls as $url) {
                if ($part = $imageToPart($url)) {
                    $parts[] = $part;
                }
            }

            // Validation: Prevent sending request without images (causes formatting hallucination)
            if (count($parts) <= 1) { // Only text part exists
                AppLogger::error("Gemini Request Aborted: No valid images could be loaded.");
                return [
                     'analysis' => 'Failed to load input images.',
                     'has_person' => false,
                     'prompt' => '',
                     'suggested_aspect_ratio' => '1:1'
                ];
            }

            $response = Http::timeout(180)->post("{$this->baseUrl}/models/gemini-2.0-flash:generateContent?key={$this->apiKey}", [
                'contents' => [
                    ['parts' => $parts]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json'
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
                
                // Clean up potential Markdown wrapping
                $text = preg_replace('/^```json\s*/', '', $text);
                $text = preg_replace('/\s*```$/', '', $text);
                $text = trim($text);
                
                // Parse JSON response
                $result = json_decode($text, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $result;
                }
                
                // Fallback if JSON parsing fails
                return [
                    'analysis' => 'Unable to parse analysis',
                    'has_person' => false,
                    'prompt' => $text,
                    'suggested_aspect_ratio' => '1:1'
                ];
            }

            AppLogger::error('Gemini analyzeImageForProductVirtual error', [
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 1000) // Log response body for debugging
            ]);
            return null;
        } catch (\Exception $e) {
            AppLogger::error('Gemini analyzeImageForProductVirtual exception', ['message' => $e->getMessage()]);
            return null;
        }
    }
}


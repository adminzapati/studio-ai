<?php

namespace App\Domain\UseCases\Features;

use App\Data\Api\GeminiClient;
use App\Core\Logging\AppLogger;

/**
 * Generate Prompt UseCase
 * 
 * Logic to generate optimized prompts using Gemini AI.
 */
class GeneratePromptUseCase
{
    public function __construct(
        private GeminiClient $geminiClient
    ) {}

    public function execute(string $type, array $data): array
    {
        try {
            $result = match ($type) {
                'image' => $this->handleImageGeneration($data),
                'wizard' => $this->handleWizardGeneration($data),
                'manual' => $this->handleManualGeneration($data),
                default => throw new \InvalidArgumentException("Invalid generation type: {$type}")
            };

            // Attempt to decode JSON if it looks like JSON, otherwise return as simple prompt
            if ($this->isJson($result)) {
                return json_decode($result, true);
            }

            return ['prompt' => $result];

        } catch (\Exception $e) {
            AppLogger::error('GeneratePromptUseCase Error', ['message' => $e->getMessage()]);
            return ['error' => "Error generating prompt: " . $e->getMessage()];
        }
    }

    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    private function handleImageGeneration(array $data): string
    {
        $image = $data['image'] ?? null;
        $notes = $data['notes'] ?? '';

        if (!$image) {
            throw new \InvalidArgumentException("Image data is required.");
        }

        $prompt = <<<PROMPT
You are a professional fashion e-commerce prompt engineer. Analyze this product image thoroughly and create an optimized AI generation prompt.

**STEP 1: DETECT IMAGE TYPE**
First, determine if this image contains:
- **TYPE A - Model Shot**: A human model wearing/holding/using the product
- **TYPE B - Product Only**: Just the product without any human

**STEP 2: ANALYZE THE IMAGE**
Provide detailed analysis in 'analysis' field including:
- Image Type (Model Shot or Product Only)
- Product type and category
- Primary and secondary colors
- Material/fabric texture appearance
- Key design features and details
- **ENVIRONMENT DESCRIPTION**: 
  - Surface the product is on (white table, wooden floor, fabric backdrop, etc.)
  - Background elements (wooden planks, gradient, plants, props, etc.)
  - Props visible (speakers, furniture, decorative items, etc.)
  - Overall setting mood (studio, outdoor, indoor lifestyle, etc.)
- Lighting conditions observed
- Camera angle (eye-level, overhead, 3/4 angle, close-up, etc.)

**STEP 3: GENERATE PROMPT**
Create prompt in 'prompt' field following this structure:

**For TYPE A (Model Shot):**
"[Photography style], [model description] wearing [GENERIC PRODUCT TYPE ONLY - no colors/materials], [pose/action], [environment: surface + background + props], [lighting description with shadows], [camera angle], [quality modifiers]. --ar [aspect ratio]"

Example: "Fashion product photography, male model wearing sandals, seated with legs extended, on white seamless backdrop against plain white background, bright diffused studio lighting with soft shadows, eye-level angle, 8K photorealistic, commercial quality. --ar 2:3"

**For TYPE B (Product Only):**
"[Photography style], [GENERIC PRODUCT TYPE ONLY - no colors/materials], placed on [surface description], [background: materials, colors, props with positions], [lighting with shadow details], [camera angle], [quality modifiers]. --ar [aspect ratio]"

Example: "A close-up product shot featuring sandals, placed on a clean white surface. Behind the product is a background of vertical wooden planks in varying shades of brown, with a square speaker in a light wood frame positioned to the side. Soft, diffused lighting with subtle shadows highlighting the texture of the wood. Eye-level angle. Studio product shot. 8K, photorealistic. --ar 2:3"

**CRITICAL RULES:**
1. **PRODUCT DESCRIPTION**: Use ONLY generic product type (sandals, sneakers, dress, jacket) - DO NOT mention colors, materials, patterns, or specific design features
2. **ENVIRONMENT**: Describe surface, background, and props in detail
3. **LIGHTING**: Include quality AND shadow descriptions
4. **CAMERA**: Specify angle clearly
5. **MODEL**: For model shots, describe vaguely (male model, female model, young woman) - not specific features or clothing details beyond the product
6. **REUSABILITY**: The prompt should work for ANY variation of that product type

Return a JSON object with two keys: 'analysis' and 'prompt'.
Output ONLY raw JSON. Do not use Markdown code blocks.
PROMPT;
        
        if (!empty($notes)) {
            $prompt .= "\n\nAdditional User Notes: " . $notes;
        }

        $result = $this->geminiClient->analyzeImage($image, $prompt);
        
        // Cleanup potential Markdown wrapping ```json ... ```
        $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? "{}";
        return $this->cleanJsonString($text);
    }

    private function handleWizardGeneration(array $data): string
    {
         // Legacy/Dev placeholder - returning simple string for now if ever enabled
        return "Wizard generation is under development.";
    }

    private function handleManualGeneration(array $data): string
    {
        $rawPrompt = $data['prompt'] ?? '';

        if (empty($rawPrompt)) {
            throw new \InvalidArgumentException("Prompt text is required.");
        }

        $prompt = <<<PROMPT
You are a professional fashion e-commerce prompt engineer specializing in AI image generation.

Optimize the following raw prompt for fashion product photography:
"{$rawPrompt}"

**Optimization Rules:**
1. **REMOVE SPECIFIC DETAILS**: Strip out specific colors, materials, patterns from the product description
2. **USE GENERIC TERMS**: Replace with simple product type only (e.g., "sandals", "sneakers", "dress")
3. **NO PLACEHOLDERS**: DO NOT use bracket placeholders like [product color] or [material]. Simply omit those details entirely
4. **ENHANCE ENVIRONMENT**: Keep and enhance surface, background, props descriptions
5. **ENHANCE LIGHTING**: Add professional photography terms (soft box, diffused, rim light, key light)
6. **ADD COMPOSITION**: Include camera angle, framing, depth of field
7. **ADD QUALITY**: Include 8K, commercial quality, professional retouching, sharp focus
8. **ADD KEYWORDS**: editorial, lookbook, catalog, e-commerce ready

**Critical Examples:**
❌ BAD: "sandals with [upper material texture] upper"
✅ GOOD: "sandals"

❌ BAD: "dress in [fabric type]"
✅ GOOD: "dress"

**Output Format:**
Return ONLY the optimized prompt text, structured as:
"[Photography style], [generic product type], [composition], [lighting], [background/environment], [quality modifiers], [mood/keywords]"

Example: "Editorial product photography, sandals, eye-level angle, shallow depth of field, soft diffused studio lighting with rim light, clean studio background, 8K commercial quality, sharp focus, e-commerce ready"

DO NOT include any explanations or markdown. Output the prompt text only.
PROMPT;

        return $this->geminiClient->generateText($prompt) ?? "Failed to optimize prompt.";
    }

    private function cleanJsonString(string $text): string
    {
        $text = preg_replace('/^```json\s*/', '', $text);
        $text = preg_replace('/\s*```$/', '', $text);
        return trim($text);
    }
}

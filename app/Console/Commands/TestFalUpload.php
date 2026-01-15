<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class TestFalUpload extends Command
{
    protected $signature = 'test:fal-upload';
    protected $description = 'Test Fal.ai V3 Upload';

    public function handle()
    {
        $apiKey = Config::get('services.fal.api_key');
        // Fallback to Setting if config is empty (mimic Client)
        if (empty($apiKey) && class_exists(\App\Models\Setting::class)) {
             try {
                 $apiKey = \App\Models\Setting::get('fal_api_key');
             } catch (\Exception $e) {}
        }

        $this->info("API Key length: " . strlen($apiKey));

        if (empty($apiKey)) {
            $this->error("API Key is missing!");
            return 1;
        }

        // Create Valid Png
        $base64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';
        $tempFile = storage_path('app/temp_test.png');
        file_put_contents($tempFile, base64_decode($base64));
        
        $fileName = 'test_pixel.png';
        $fileContents = file_get_contents($tempFile);
        $contentType = 'image/png';

        // Candidate Endpoints
        $candidates = [
            'https://fal.run/v1/storage/upload/initiate',
            'https://fal.run/storage/upload/initiate',
            'https://fal.run/fal-ai/storage/upload/initiate',
            'https://fal.run/fal-ai/v1/storage/upload/initiate',
            'https://fal.media/files/upload', // Check behavior with valid key
        ];

        foreach ($candidates as $endpoint) {
            $this->info("Testing Endpoint: $endpoint");
            try {
                $response = Http::withHeaders([
                    'Authorization' => "Key $apiKey",
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->post($endpoint, [
                    'file_name' => $fileName,
                    'content_type' => $contentType,
                ]);

                if ($response->successful()) {
                    $this->info("SUCCESS! " . $response->body());
                    $data = $response->json();
                    $uploadUrl = $data['upload_url'];
                    $fileUrl = $data['file_url'];

                    // Do the PUT
                    $this->info("Uploading to: $uploadUrl");
                    $put = Http::withBody($fileContents, $contentType)
                        ->withHeaders(['Content-Type' => $contentType]) // Strict content type
                        ->put($uploadUrl);
                    
                    if ($put->successful()) {
                        $this->info("PUT Success. Final URL: $fileUrl");
                        // 100% Verified
                        return 0;
                    } else {
                        $this->error("PUT Failed: " . $put->status());
                    }
                } else {
                    $this->warn("Failed: " . $response->status() . " Body: " . $response->body());
                }
            } catch (\Exception $e) {
                $this->error("Ex: " . $e->getMessage());
            }
        }

        $this->error("All candidates failed.");
        return 1;
    }
}

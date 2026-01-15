<?php
// Validates Fal.ai V3 Upload Flow
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

$apiKey = Config::get('services.fal_ai.key');
echo "API Key: " . substr($apiKey, 0, 5) . "...\n";

// 1. Create a dummy test image (PNG) using Base64
$base64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';
$tempFile = sys_get_temp_dir() . '/test_fal_v3.png';
file_put_contents($tempFile, base64_decode($base64));
echo "Created temp image: $tempFile\n";

$fileContents = file_get_contents($tempFile);
$fileSize = strlen($fileContents);
$fileName = basename($tempFile);
$contentType = 'image/png';

try {
    // 2. INITIATE UPLOAD (V3)
    $candidates = [
        'https://fal.run/fal-ai/v2/storage/upload/initiate',
        'https://fal.run/fal-ai/fast-sdxl/image-to-image/storage/upload/initiate',
    ];

    $uploadUrl = null;
    $fileUrl = null;

    echo "\n--- CURL TEST for fal.media ---\n";
    $curlCmd = "curl -X POST https://fal.media/files/upload " .
               "-H \"Authorization: Key $apiKey\" " .
               "-H \"Accept: application/json\" " .
               "-F \"file=@$tempFile;type=image/png\"";
    
    echo "Running CURL...\n";
    $output = shell_exec($curlCmd);
    echo "CURL Output: $output\n";

    $json = json_decode($output, true);
    if (isset($json['access_url'])) {
        $binUrl = $json['access_url'];
        $pngUrl = preg_replace('/\.bin$/', '.png', $binUrl);
        echo "Bin URL: $binUrl\n";
        echo "Png URL (Proposed): $pngUrl\n";
        
        echo "Checking if Png URL works...\n";
        $headers = shell_exec("curl -I \"$pngUrl\"");
        echo "Headers:\n$headers\n";
    }

    /*
    foreach ($candidates as $endpoint) {
       // ... (disabled for now)
    } 
    */
    exit;

            $response = Http::withHeaders([
                'Authorization' => "Key $apiKey",
                'Content-Type' => 'application/json',
            ])->post($endpoint, [
                'file_name' => $fileName,
                'content_type' => $contentType,
            ]);

            if ($response->successful()) {
                $initData = $response->json();
                $uploadUrl = $initData['upload_url'] ?? null;
                $fileUrl = $initData['file_url'] ?? null;
                if ($uploadUrl && $fileUrl) {
                    echo "SUCCESS with $endpoint\n";
                    break;
                }
            } else {
                echo "Failed: " . $response->status() . "\n";
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    if (!$uploadUrl) {
        echo "All candidates failed.\n";
        exit(1);
    }


    // 3. UPLOAD FILE (PUT)
    echo "Uploading file...\n";
    // For signed URLs, we usually PUT purely the binary content
    // Headers might be required if signed URL mandates content-type
    
    $putResponse = Http::withHeaders([
        'Content-Type' => $contentType, 
        // Note: For S3 signed URLs, we MUST match the content-type used in signature
    ])->withBody($fileContents, $contentType)->put($uploadUrl);

    if (!$putResponse->successful()) {
        echo "Upload PUT Failed: " . $putResponse->status() . " " . $putResponse->body() . "\n";
        exit(1);
    }
    
    echo "Upload PUT Success.\n";
    echo "FINAL URL: $fileUrl\n";

    // 4. Verify Access
    // echo "Verifying ability to download...\n";
    // $check = Http::get($fileUrl);
    // echo "Check Status: " . $check->status() . "\n";

} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

<?php
// Validate "Rename .bin to .png" hack
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Config;

$apiKey = Config::get('services.fal.api_key');
// Fallback if config is null (e.g. CLI cache issue despite clear)
if (empty($apiKey)) {
    // Try env directly if Dotenv loaded
    $apiKey = env('FAL_API_KEY');
}
echo "API Key loaded: " . (strlen($apiKey) > 5 ? 'Yes' : 'No') . "\n";

// 1. Create Base64 Image (Red Pixel)
$base64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';
$tempFile = sys_get_temp_dir() . '/test_fal_hack.png';
file_put_contents($tempFile, base64_decode($base64));
echo "Created temp image: $tempFile\n";

// 2. CURL Upload
$curlCmd = "curl -X POST https://fal.media/files/upload " .
           "-H \"Authorization: Key $apiKey\" " .
           "-H \"Accept: application/json\" " .
           "-F \"file=@$tempFile;type=image/png\""; // Explicit type

echo "Uploading via CURL...\n";
$output = shell_exec($curlCmd);
echo "Output: $output\n";

$json = json_decode($output, true);
if (!$json || !isset($json['access_url'])) {
    echo "Upload failed.\n";
    exit(1);
}

$binUrl = $json['access_url'];
echo "Original URL: $binUrl\n";

// 3. Rename Hack
$pngUrl = preg_replace('/\.bin$/', '.png', $binUrl);
echo "Proposed URL: $pngUrl\n";

// 4. Verify Access
echo "Checking Access (HEAD)...\n";
$headers = shell_exec("curl -I \"$pngUrl\"");
echo "Headers:\n$headers\n";

if (strpos($headers, '200 OK') !== false) {
    echo "SUCCESS: HACK WORKS!\n";
} else {
    echo "FAILURE: HACK FAILED.\n";
}

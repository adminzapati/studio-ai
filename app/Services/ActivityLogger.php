<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Activity Logger Service
 * 
 * Centralized service for logging user activities across the application.
 */
class ActivityLogger
{
    /**
     * Log an activity.
     * 
     * @param array $data Activity data
     * @return ActivityLog
     */
    public static function log(array $data): ActivityLog
    {
        // Ensure user_id is set
        if (!isset($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }

        return ActivityLog::create($data);
    }

    /**
     * Log Products Virtual generation.
     */
    public static function logProductsVirtualGeneration(
        int $jobId,
        string $prompt,
        string $thumbnailPath,
        array $additionalData = []
    ): ActivityLog {
        return self::log([
            'action_type' => 'generated',
            'module' => 'products_virtual',
            'description' => 'Generated virtual try-on image',
            'thumbnail_path' => $thumbnailPath,
            'related_id' => $jobId,
            'metadata' => array_merge([
                'prompt' => $prompt,
            ], $additionalData),
        ]);
    }

    /**
     * Log prompt creation.
     */
    public static function logPromptCreated(int $promptId, string $name, ?string $imagePath = null): ActivityLog
    {
        return self::log([
            'action_type' => 'created',
            'module' => 'prompts',
            'description' => "Created prompt: {$name}",
            'thumbnail_path' => $imagePath,
            'related_id' => $promptId,
        ]);
    }

    /**
     * Log prompt update.
     */
    public static function logPromptUpdated(int $promptId, string $name): ActivityLog
    {
        return self::log([
            'action_type' => 'updated',
            'module' => 'prompts',
            'description' => "Updated prompt: {$name}",
            'related_id' => $promptId,
        ]);
    }

    /**
     * Log prompt deletion.
     */
    public static function logPromptDeleted(string $name): ActivityLog
    {
        return self::log([
            'action_type' => 'deleted',
            'module' => 'prompts',
            'description' => "Deleted prompt: {$name}",
        ]);
    }

    /**
     * Log image upload.
     */
    public static function logImageUploaded(int $imageId, string $fileName, string $thumbnailPath): ActivityLog
    {
        return self::log([
            'action_type' => 'uploaded',
            'module' => 'images',
            'description' => "Uploaded image: {$fileName}",
            'thumbnail_path' => $thumbnailPath,
            'related_id' => $imageId,
        ]);
    }

    /**
     * Log image deletion.
     */
    public static function logImageDeleted(string $fileName): ActivityLog
    {
        return self::log([
            'action_type' => 'deleted',
            'module' => 'images',
            'description' => "Deleted image: {$fileName}",
        ]);
    }

    /**
     * Log batch processing.
     */
    public static function logBatchProcessed(int $batchId, int $count): ActivityLog
    {
        return self::log([
            'action_type' => 'generated',
            'module' => 'batch',
            'description' => "Processed batch of {$count} images",
            'related_id' => $batchId,
            'metadata' => ['count' => $count],
        ]);
    }
}

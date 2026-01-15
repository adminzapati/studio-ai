<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Products Virtual Job Model
 * 
 * Stores history of Products Virtual generation jobs.
 */
class ProductsVirtualJob extends Model
{
    protected $fillable = [
        'user_id',
        'model_image_fal_url',
        'model_image_path', // Added
        'model_image_library_id',
        'product_images_fal_urls',
        'product_images_paths', // Added
        'model_preset_id',
        'gemini_prompt',
        'refined_prompt',
        'size_ratio',
        'background',
        'quality',
        'format',
        'status',
        'result_image_path',
        'error_message',
        'is_favorite',
    ];

    protected $casts = [
        'product_images_fal_urls' => 'array',
        'product_images_paths' => 'array', // Added
        'is_favorite' => 'boolean',
    ];

    /**
     * Get the user that owns the job.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model preset used for this job.
     */
    public function modelPreset(): BelongsTo
    {
        return $this->belongsTo(ModelPreset::class);
    }

    /**
     * Get the model image from library if used.
     */
    public function modelImageLibrary(): BelongsTo
    {
        return $this->belongsTo(ImageLibrary::class, 'model_image_library_id');
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if job is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if job failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if job is still processing.
     */
    public function isProcessing(): bool
    {
        return in_array($this->status, ['pending', 'analyzing', 'generating']);
    }
}

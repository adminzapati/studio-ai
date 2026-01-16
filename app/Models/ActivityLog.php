<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Activity Log Model
 * 
 * Tracks all user activities across Features and Storage modules.
 */
class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action_type',
        'module',
        'description',
        'metadata',
        'thumbnail_path',
        'related_id',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that performed this activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter activities for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by module.
     */
    public function scopeByModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope to filter by action type.
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action_type', $action);
    }

    /**
     * Scope to search in description.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('description', 'like', "%{$search}%");
    }

    /**
     * Get formatted time ago string.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get module badge color class.
     */
    public function getModuleBadgeColorAttribute(): string
    {
        return match($this->module) {
            'products_virtual' => 'bg-pink-50 text-pink-700 dark:bg-pink-900/20 dark:text-pink-400',
            'prompts' => 'bg-purple-50 text-purple-700 dark:bg-purple-900/20 dark:text-purple-400',
            'images' => 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400',
            'batch' => 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400',
            default => 'bg-gray-50 text-gray-700 dark:bg-gray-900/20 dark:text-gray-400',
        };
    }

    /**
     * Get action icon SVG path.
     */
    public function getActionIconAttribute(): string
    {
        return match($this->action_type) {
            'created' => 'M12 6v6m0 0v6m0-6h6m-6 0H6', // Plus
            'updated' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', // Edit
            'deleted' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16', // Trash
            'generated' => 'M5 3l14 9-14 9V3z', // Play/Generate
            'uploaded' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12', // Upload
            'downloaded' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4', // Download
            default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', // Info
        };
    }
}

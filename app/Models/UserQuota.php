<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * User Quota Model
 * 
 * Tracks generation quota for each user.
 */
class UserQuota extends Model
{
    protected $fillable = [
        'user_id',
        'daily_generations',
        'total_generations',
        'last_generation_date',
    ];

    protected $casts = [
        'last_generation_date' => 'datetime',
    ];

    /**
     * Get the user that owns the quota.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user can generate (considering daily reset and limits).
     */
    public function canGenerate(): bool
    {
        // Admin always can
        if ($this->user && $this->user->hasRole('Admin')) {
            return true;
        }

        // Get limits from settings
        $dailyLimit = (int) Setting::get('products_virtual_daily_limit', 10);
        $totalLimit = (int) Setting::get('products_virtual_total_limit', 100);

        // Reset daily count if new day
        $dailyCount = $this->getDailyCount();

        return $dailyCount < $dailyLimit && $this->total_generations < $totalLimit;
    }

    /**
     * Get remaining daily quota.
     */
    public function getRemainingDailyQuota(): int
    {
        if ($this->user && $this->user->hasRole('Admin')) {
            return -1; // Unlimited
        }

        $dailyLimit = (int) Setting::get('products_virtual_daily_limit', 10);
        return max(0, $dailyLimit - $this->getDailyCount());
    }

    /**
     * Get remaining total quota.
     */
    public function getRemainingTotalQuota(): int
    {
        if ($this->user && $this->user->hasRole('Admin')) {
            return -1; // Unlimited
        }

        $totalLimit = (int) Setting::get('products_virtual_total_limit', 100);
        return max(0, $totalLimit - $this->total_generations);
    }

    /**
     * Get daily count, considering day reset.
     */
    protected function getDailyCount(): int
    {
        if (!$this->last_generation_date || !$this->last_generation_date->isToday()) {
            return 0;
        }
        return $this->daily_generations;
    }

    /**
     * Record a generation (increment counters).
     */
    public function recordGeneration(): void
    {
        // Reset daily count if new day
        if (!$this->last_generation_date || !$this->last_generation_date->isToday()) {
            $this->daily_generations = 1;
        } else {
            $this->daily_generations++;
        }

        $this->total_generations++;
        $this->last_generation_date = now();
        $this->save();
    }

    /**
     * Get or create quota for a user.
     */
    public static function getOrCreateForUser(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            ['daily_generations' => 0, 'total_generations' => 0]
        );
    }
}

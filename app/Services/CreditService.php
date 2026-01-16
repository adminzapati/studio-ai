<?php

namespace App\Services;

use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Auth;
use App\Models\CreditTransaction;

/**
 * Credit Service
 * 
 * Handles all credit calculations and deductions.
 */
class CreditService
{
    /**
     * Calculate credit cost for image generation.
     * 
     * Formula:
     * Low Quality (1 credit base)
     * Medium Quality (4 credits base)
     * High Quality (15 credits base)
     * 
     * Ratios:
     * 1:1 = 1.0x
     * 2:3/3:2/4:3/3:4 = 1.5x
     * 16:9/9:16 = 1.8x
     */
    public static function calculateCost(string $quality, string $ratio): int
    {
        // Base cost by quality
        $baseCredits = match($quality) {
            'low' => 1, // $0.009
            'medium' => 4, // $0.034
            'high' => 15, // $0.133
            default => 4,
        };
        
        // Multiplier by ratio (area complexity)
        $multiplier = match($ratio) {
            'square_hd' => 1.0, // 1024x1024
            'portrait_4_3', 'landscape_4_3' => 1.3,
            'portrait_16_9', 'landscape_16_9' => 1.8,
            default => 1.0,
        };
        
        return (int) ceil($baseCredits * $multiplier);
    }

    /**
     * Check if user has enough credits.
     */
    public static function hasEnoughCredits(int $userId, int $cost): bool
    {
        $subscription = UserSubscription::where('user_id', $userId)
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            return false;
        }

        // Check if plan expired (if applicable)
        if ($subscription->billing_cycle_end && $subscription->billing_cycle_end->isPast()) {
            return false;
        }

        return $subscription->credits_remaining >= $cost;
    }

    /**
     * Deduct credits from user subscription.
     */
    public static function deductCredits(int $userId, int $amount, string $description, array $metadata = []): bool
    {
        $subscription = UserSubscription::where('user_id', $userId)
            ->where('status', 'active')
            ->first();

        if (!$subscription || $subscription->credits_remaining < $amount) {
            return false;
        }

        // Deduct credits
        $subscription->decrement('credits_remaining', $amount);
        $subscription->increment('credits_used_this_month', $amount);

        // Record transaction
        CreditTransaction::create([
            'user_id' => $userId,
            'amount' => -$amount,
            'type' => 'usage',
            'description' => $description,
            'metadata' => $metadata,
        ]);

        return true;
    }

    /**
     * Add credits (refund or bonus).
     */
    public static function addCredits(int $userId, int $amount, string $description, string $type = 'bonus'): void
    {
        $subscription = UserSubscription::firstOrCreate(
            ['user_id' => $userId, 'status' => 'active'],
            [
                'subscription_plan_id' => 1, // Default Free
                'credits_remaining' => 0,
                'billing_cycle_start' => now(),
            ]
        );

        $subscription->increment('credits_remaining', $amount);

        CreditTransaction::create([
            'user_id' => $userId,
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
        ]);
    }
}

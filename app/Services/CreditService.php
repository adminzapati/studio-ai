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
     * Pricing Table:
     * - Low 1:1 = 1 credit
     * - Low 2:3/3:2 = 1.5 credits
     * - Medium 1:1 = 4 credits
     * - Medium 2:3/3:2 = 6 credits
     * - High 1:1 = 15 credits
     * - High 2:3/3:2 = 22 credits
     */
    public static function calculateCost(string $quality, string $ratio): int
    {
        // Determine if ratio is square (1:1) or rectangular (2:3, 3:2, etc.)
        $isSquare = in_array($ratio, ['1:1', 'square', 'square_hd']);
        
        // Calculate based on quality and ratio
        $credits = match([$quality, $isSquare]) {
            // Low quality
            ['low', true] => 1,      // 1:1
            ['low', false] => 1.5,   // 2:3 or 3:2
            
            // Medium quality
            ['medium', true] => 4,   // 1:1
            ['medium', false] => 6,  // 2:3 or 3:2
            
            // High quality
            ['high', true] => 15,    // 1:1
            ['high', false] => 22,   // 2:3 or 3:2
            
            // Default fallback (medium 1:1)
            default => 4,
        };
        
        return (int) ceil($credits);
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

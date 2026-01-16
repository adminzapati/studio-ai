<?php

namespace App\Services;

use App\Models\FeatureModule;
use App\Models\UserModuleOverride;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Cache;

/**
 * Module Service
 * 
 * Handles module access logic:
 * 1. User Override (Highest priority)
 * 2. Subscription Plan
 * 3. Global Module Status
 */
class ModuleService
{
    /**
     * Check if user has access to a specific module.
     */
    public static function hasAccess(int $userId, string $moduleSlug): bool
    {
        // 1. Check User Override
        $override = UserModuleOverride::where('user_id', $userId)
            ->whereHas('module', function ($query) use ($moduleSlug) {
                $query->where('slug', $moduleSlug);
            })
            ->first();

        if ($override) {
            return $override->is_enabled;
        }

        // 2. Check Global Module Status (If globally disabled, plan can't enable it unless override exists? 
        // Or should plan be respected? Usually Global Kill Switch > Plan. 
        // But Override > Global Kill Switch for testing.
        // Let's go with: If globally disabled, ONLY Override can enable it.)
        
        $module = Cache::remember('module_' . $moduleSlug, 3600, function () use ($moduleSlug) {
            return FeatureModule::where('slug', $moduleSlug)->first();
        });

        if (!$module) {
            return false; // Module doesn't exist
        }

        if (!$module->is_enabled) {
            return false; // Globally disabled (unless override, which we checked above)
        }

        // 3. Check Subscription Plan
        $subscription = UserSubscription::with('plan')
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->first();

        if (!$subscription || !$subscription->plan) {
            // Check for default/free plan logic if no sub? 
            // Or assume no sub = no access to anything except free stuff if hardcoded?
            // Ideally everyone should have a subscription record (Free tier).
            return false; 
        }

        $allowedModules = $subscription->plan->modules ?? [];
        
        // Handle "all" or specific list
        if (in_array('*', $allowedModules)) {
            return true;
        }

        return in_array($moduleSlug, $allowedModules);
    }
}

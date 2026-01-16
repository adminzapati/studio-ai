<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\CreditTransaction;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Display pricing plans.
     */
    public function index()
    {
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('sort_order')->get();
        
        $currentSubscription = UserSubscription::where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        $pendingRequest = \App\Models\SubscriptionRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        return view('subscription.index', compact('plans', 'currentSubscription', 'pendingRequest'));
    }

    /**
     * Switch plan (Simplified for MVP - Direct switch without payment gateway).
     */
    public function update(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        try {
        // Check if there is already a pending request
        $pendingRequest = \App\Models\SubscriptionRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($pendingRequest) {
            return back()->with('error', 'You already have a pending subscription request. Please wait for admin approval.');
        }

        try {
            // Create subscription request
            \App\Models\SubscriptionRequest::create([
                'user_id' => auth()->id(),
                'subscription_plan_id' => $plan->id,
                'status' => 'pending',
            ]);

            return redirect()->route('subscription.index')->with('success', "Request to switch to {$plan->name} plan submitted. Waiting for admin approval.");

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to submit subscription request. ' . $e->getMessage());
        }

            return redirect()->route('subscription.index')->with('success', "Switched to {$plan->name} plan successfully.");

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update subscription. ' . $e->getMessage());
        }
    }
}

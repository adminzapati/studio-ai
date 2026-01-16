<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionRequest;
use App\Models\UserSubscription;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionRequestController extends Controller
{
    /**
     * Display pending requests.
     */
    public function index()
    {
        $requests = SubscriptionRequest::with(['user', 'plan'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);
            
        return view('admin.subscription_requests.index', compact('requests'));
    }

    /**
     * Approve request.
     */
    public function approve(SubscriptionRequest $subscriptionRequest)
    {
        if ($subscriptionRequest->status !== 'pending') {
            return back()->with('error', 'Request already processed.');
        }

        try {
            DB::transaction(function () use ($subscriptionRequest) {
                // 1. Update Request Status
                $subscriptionRequest->update(['status' => 'approved']);

                // 2. Deactivate current active subscription
                $currentSubscription = UserSubscription::where('user_id', $subscriptionRequest->user_id)
                    ->where('status', 'active')
                    ->first();
                
                $previousCredits = 0;
                if ($currentSubscription) {
                    $previousCredits = $currentSubscription->credits_remaining;
                    $currentSubscription->update(['status' => 'cancelled']);
                }

                // 3. Create new subscription
                $plan = $subscriptionRequest->plan;
                $newTotalCredits = $plan->credits_monthly + $previousCredits;
                
                UserSubscription::create([
                    'user_id' => $subscriptionRequest->user_id,
                    'subscription_plan_id' => $plan->id,
                    'status' => 'active',
                    'credits_remaining' => $newTotalCredits,
                    'credits_used_this_month' => 0,
                    'billing_cycle_start' => now(),
                    'billing_cycle_end' => now()->addMonth(),
                ]);

                // 4. Log Transaction
                CreditTransaction::create([
                    'user_id' => $subscriptionRequest->user_id,
                    'amount' => $plan->credits_monthly,
                    'type' => 'subscription',
                    'description' => "Subscribed to {$plan->name} Plan (Approved by Admin)",
                    'metadata' => [
                        'plan_id' => $plan->id, 
                        'request_id' => $subscriptionRequest->id
                    ]
                ]);
            });

            return back()->with('success', 'Subscription request approved successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve request. ' . $e->getMessage());
        }
    }

    /**
     * Reject request.
     */
    public function reject(Request $request, SubscriptionRequest $subscriptionRequest)
    {
        if ($subscriptionRequest->status !== 'pending') {
            return back()->with('error', 'Request already processed.');
        }

        $subscriptionRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->input('admin_notes', 'Rejected by admin.')
        ]);

        return back()->with('success', 'Subscription request rejected.');
    }
}

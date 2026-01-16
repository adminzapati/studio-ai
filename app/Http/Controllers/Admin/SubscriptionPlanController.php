<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\FeatureModule;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of plans.
     */
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('sort_order')->get();
        return view('admin.subscription_plans.index', compact('plans'));
    }

    /**
     * Show the form for editing a plan.
     */
    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        $modules = FeatureModule::orderBy('sort_order')->get();
        $planModules = $subscriptionPlan->modules ?? [];

        return view('admin.subscription_plans.edit', compact('subscriptionPlan', 'modules', 'planModules'));
    }

    /**
     * Update the plan.
     */
    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'credits_monthly' => 'required|integer|min:0',
            'modules' => 'nullable|array',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $subscriptionPlan->update([
            'name' => $request->name,
            'price' => $request->price,
            'credits_monthly' => $request->credits_monthly,
            'modules' => $request->modules ?? [],
            'features' => $request->features ?? [],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan updated successfully.');
    }
}

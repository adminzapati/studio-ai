<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use App\Models\FeatureModule;
use App\Models\UserModuleOverride;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['roles', 'activeSubscription.plan'])->latest()->paginate(10);
        $plans = \App\Models\SubscriptionPlan::orderBy('sort_order')->get();
        return view('admin.users.index', compact('users', 'plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
            'is_active' => ['boolean']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->has('is_active') ? $request->is_active : true,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not implemented for this version
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $modules = FeatureModule::orderBy('sort_order')->get();
        // Load user's overrides
        $userOverrides = UserModuleOverride::where('user_id', $user->id)
            ->pluck('is_enabled', 'feature_module_id')
            ->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'modules', 'userOverrides'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'exists:roles,name'],
            'is_active' => ['boolean']
        ]);
        
        // Only validate password if it's provided
        if ($request->filled('password')) {
             $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
             ]);
             $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_active = $request->boolean('is_active');
        $user->save();

        $user->syncRoles([$request->role]);

        // Sync Module Overrides
        if ($request->has('module_overrides')) {
            foreach ($request->module_overrides as $moduleId => $status) {
                if ($status === 'default') {
                    // Remove override (use plan setting)
                    UserModuleOverride::where('user_id', $user->id)
                        ->where('feature_module_id', $moduleId)
                        ->delete();
                } else {
                    // Create/Update override
                    UserModuleOverride::updateOrCreate(
                        ['user_id' => $user->id, 'feature_module_id' => $moduleId],
                        ['is_enabled' => $status == '1']
                    );
                }
            }
        }

        // Manual Credit Adjustment
        if ($request->filled('manual_credits')) {
            $newCredits = (int) $request->manual_credits;
            if ($user->activeSubscription) {
                 $user->activeSubscription->update(['credits_remaining' => $newCredits]);
            }
        }

        // Handle Subscription Plan Change (Force Update)
        if ($request->filled('subscription_plan_id')) {
            $user->activeSubscription->update(['status' => 'cancelled']); // End current
            
            $newPlan = \App\Models\SubscriptionPlan::find($request->subscription_plan_id);
            if ($newPlan) {
                \App\Models\UserSubscription::create([
                    'user_id' => $user->id,
                    'subscription_plan_id' => $newPlan->id,
                    'status' => 'active',
                    'credits_remaining' => $newPlan->credits_monthly,
                    'credits_used_this_month' => 0,
                    'billing_cycle_start' => now(),
                    'billing_cycle_end' => now()->addMonth(),
                ]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
    
    /**
     * Toggle lock status.
     */
    public function toggleLock(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot lock yourself.');
        }

        $user->is_active = !$user->is_active;
        $user->save();
        
        $status = $user->is_active ? 'unlocked' : 'locked';
        return back()->with('success', "User has been {$status}.");
    }
}

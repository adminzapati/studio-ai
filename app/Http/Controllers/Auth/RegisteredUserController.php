<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => false, // Default to inactive
        ]);

        // Auto-assign Free subscription plan
        $freePlan = \App\Models\SubscriptionPlan::where('slug', 'free')->first();
        if ($freePlan) {
            \App\Models\UserSubscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $freePlan->id,
                'status' => 'active',
                'credits_remaining' => $freePlan->credits_monthly,
                'credits_used_this_month' => 0,
                'billing_cycle_start' => now(),
                'billing_cycle_end' => now()->addMonth(),
            ]);
        }

        event(new Registered($user));

        // Notify Admins
        $admins = User::role('Admin')->get();
        if ($admins->count() > 0) {
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewUserRegistration($user));
        }

        // Auth::login($user); // Do not login automatically

        return redirect(route('login'))->with('status', 'Registration successful! Your account is pending approval by an administrator.');
    }
}

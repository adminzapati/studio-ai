<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'status',
        'credits_remaining',
        'credits_used_this_month',
        'billing_cycle_start',
        'billing_cycle_end',
    ];

    protected $casts = [
        'billing_cycle_start' => 'date',
        'billing_cycle_end' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function isValid(): bool
    {
        return $this->status === 'active' && 
               ($this->billing_cycle_end === null || $this->billing_cycle_end->isFuture());
    }

    public function hasCredits(int $amount): bool
    {
        return $this->credits_remaining >= $amount;
    }
}

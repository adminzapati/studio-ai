<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserSubscription;
use App\Models\CreditTransaction;
use Carbon\Carbon;

class ResetMonthlyCredits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credits:reset-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset user credits at the start of their billing cycle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $this->info("Starting credit reset for {$now->toDateTimeString()}");

        // Find active subscriptions that have passed their billing cycle end
        $subscriptions = UserSubscription::where('status', 'active')
            ->where('billing_cycle_end', '<=', $now)
            ->with('plan')
            ->get();

        $count = 0;

        foreach ($subscriptions as $subscription) {
            if (!$subscription->plan) {
                // If plan is deleted, maybe cancel subscription?
                $this->warn("Subscription {$subscription->id} has no plan. Skipping.");
                continue;
            }

            // Calculate new cycle dates
            $newStart = $subscription->billing_cycle_end; // Use old end as new start to prevent drift? Or just Now?
            // Usually use old end to keep day of month consistent.
            $newEnd = $newStart->copy()->addMonth();

            // Reset Credits
            // Policy: Reset to Plan Amount (Use or Lose). 
            // Optional: Rollover logic could go here.
            $resetAmount = $subscription->plan->credits_monthly;
            
            $subscription->update([
                'credits_remaining' => $resetAmount,
                'credits_used_this_month' => 0,
                'billing_cycle_start' => $newStart,
                'billing_cycle_end' => $newEnd,
            ]);

            // Log Transaction (System Reset)
            CreditTransaction::create([
                'user_id' => $subscription->user_id,
                'amount' => $resetAmount, // Showing the "Deposit" of new credits? 
                // Or just Log 0 amount with "Reset" description?
                // Usually nicer to see "+100 Credits (Monthly Reset)"
                'type' => 'system',
                'description' => "Monthly Credit Reset ({$subscription->plan->name})",
                'metadata' => [
                    'billing_cycle' => $newStart->toDateString() . ' to ' . $newEnd->toDateString()
                ]
            ]);

            $count++;
        }

        $this->info("Processed {$count} subscriptions.");
    }
}

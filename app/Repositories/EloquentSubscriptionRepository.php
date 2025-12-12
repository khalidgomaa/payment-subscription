<?php
namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Subscription;
use App\Enums\SubscriptionStatusEnum;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;

class EloquentSubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function createPending(User $user, int $planId): Subscription
    {
        return Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $planId,
            'status' => SubscriptionStatusEnum::Pending,
        ]);
    }
    public function findByPaymentId(string $paymentId): ?Subscription
        {
            return Subscription::where('payment_id', $paymentId)->first();
        }
    public function updatePaymentId(int $subscriptionId, string $paymentId): void
        {
            Subscription::where('id', $subscriptionId)
                ->update(['payment_id' => $paymentId]);
        }

    public function markAsActive(int $subscriptionId, int $durationDays): ?Subscription
    {
        $now = Carbon::now();
        $subscription = Subscription::find($subscriptionId);

        if ($subscription) {
            $subscription->update([
                'status' => SubscriptionStatusEnum::Active,
                'start_date' => $now,
                'end_date' => $now->copy()->addDays($durationDays),
            ]);
        }

        return $subscription;
    }

    public function findBySessionId(string $sessionId): ?Subscription
    {
        return Subscription::where('stripe_session_id', $sessionId)->first();
    }

    public function deactivate(int $subscriptionId): ?Subscription
    {
        $subscription = Subscription::find($subscriptionId);
        if ($subscription && $subscription->status !== SubscriptionStatusEnum::Canceled) {
            $subscription->update(['status' => SubscriptionStatusEnum::Canceled]);
        }
        return $subscription;
    }

    public function getUserActiveSubscription(int $userId): ?Subscription
    {
        return Subscription::where('user_id', $userId)
            ->active()
            ->latest()
            ->first();
    }
}
<?php
namespace App\Repositories\Contracts;

use App\Models\Subscription;
use App\Models\User;

interface SubscriptionRepositoryInterface
{
public function createPending(User $user, int $planId): Subscription;
    public function updatePaymentId(int $subscriptionId, string $paymentId): void;
    public function markAsActive(int $subscriptionId, int $durationDays): ?Subscription;
    public function findByPaymentId(string $paymentId): ?Subscription;
    public function deactivate(int $subscriptionId): ?Subscription;
    public function getUserActiveSubscription(int $userId): ?Subscription;
}

<?php
namespace App\Services;


use Exception;
use App\Models\Plan;
use App\Models\User;
use App\Models\Subscription;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    public function __construct(protected SubscriptionRepositoryInterface $subscriptionRepository)
    {
    }
    public function getCurrentActiveSubscription(int $userId): ?Subscription
        {
            return $this->subscriptionRepository->getUserActiveSubscription($userId);
        }
    public function createCheckoutSession(User $user, Plan $plan): string
    {
        if ($this->subscriptionRepository->getUserActiveSubscription($user->id)) {
            throw new Exception('User already has an active subscription.');
        }

        $subscription = $this->subscriptionRepository->createPending($user, $plan->id);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [$this->buildCheckoutLineItems($plan)], // Use helper method
            'mode' => 'payment',
            'success_url' => config('app.url') . '/api/subscriptions/payment/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => config('app.url') . '/api/subscriptions/payment/cancel',
            'client_reference_id' => $subscription->id,
            'metadata' => $this->buildCheckoutMetadata($user, $plan), // Use helper method
        ]);

        $this->subscriptionRepository->updatePaymentId($subscription->id, $session->id);

        return $session->url;
    }

    protected function buildCheckoutLineItems(Plan $plan): array
    {
        return [
            'price_data' => [
                'currency' => config('services.stripe.currency'),
                'product_data' => [
                    'name' => $plan->name->value,
                    'description' => "Subscription to {$plan->name->value} plan for {$plan->duration_days} days.",
                ],
                'unit_amount' => $plan->price * 100,
            ],
            'quantity' => 1,
        ];
    }

    protected function buildCheckoutMetadata(User $user, Plan $plan): array
    {
        return [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
        ];
    }

    public function activateSubscription(string $paymentId): ?Subscription
    {
        $subscription = $this->subscriptionRepository->findByPaymentId($paymentId);

        if (!$subscription || $subscription->status->value !== 'pending') {
            return null;
        }

        $plan = Plan::find($subscription->plan_id); // Get the plan
        if (!$plan) {
            throw new Exception('Plan not found for subscription ID: ' . $subscription->id);
        }

        return $this->subscriptionRepository->markAsActive($subscription->id, $plan->duration_days);
    }

    public function deactivateSubscription(int $subscriptionId): ?Subscription
    {
         return $this->subscriptionRepository->deactivate($subscriptionId);
    }
}
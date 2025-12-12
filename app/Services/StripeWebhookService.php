<?php

namespace App\Services;

use App\Services\Contracts\StripeWebhookServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;

class StripeWebhookService implements StripeWebhookServiceInterface
{
    public function __construct(protected SubscriptionService $subscriptionService)
    {
    }

    public function handleWebhook(Request $request): bool
    {
        $webhookSecret = config('services.stripe.webhook_secret');
        $signature = $request->header('Stripe-Signature');

        if (!$webhookSecret || !$signature) {
            return false; 
        }

        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $signature,
                $webhookSecret
            );
        } catch (Exception $e) {
            return false;
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $paymentId = $session->id; 

                try {
                    $this->subscriptionService->activateSubscription($paymentId);
                    Log::info('Subscription activated successfully for payment ID: ' . $paymentId);
                } catch (Exception $e) {
                    Log::error("Failed to activate subscription for payment ID {$paymentId}: " . $e->getMessage());
                    return false;
                }
                break;
            
            default:
                break;
        }

        return true;
    }
}


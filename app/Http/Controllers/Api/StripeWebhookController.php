<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use Exception;
use App\Enums\HttpStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Stripe\Webhook;
use App\Services\Contracts\StripeWebhookServiceInterface;

class StripeWebhookController extends Controller
{
    public function __construct(protected SubscriptionService $subscriptionService, protected StripeWebhookServiceInterface $stripeWebhookService)
    {
    }

    public function __invoke(Request $request)
    {
        $success = $this->stripeWebhookService->handleWebhook($request);

        if (!$success) {
            return Response::apiResponse(HttpStatus::BAD_REQUEST, null, 'Webhook processing failed');
        }

        return Response::apiResponse(HttpStatus::OK, ['status' => 'success']);
    }
}
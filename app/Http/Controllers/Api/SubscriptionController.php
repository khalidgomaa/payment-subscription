<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Enums\HttpStatus; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response; 
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Services\SubscriptionService;
use App\Http\Requests\PurchaseSubscriptionRequest;
use App\Repositories\Contracts\PlanRepositoryInterface;

class SubscriptionController extends Controller
{
    public function __construct(
        protected PlanRepositoryInterface $planRepository,
        protected SubscriptionService $subscriptionService
    ) {
    }
    public function listPlans()
    {
        $plans = $this->planRepository->all();
        return Response::apiResponse(
            HttpStatus::OK,
            PlanResource::collection($plans),
            'Available plans retrieved successfully'
        );
    }

    /**
     *
     */
    public function purchase(PurchaseSubscriptionRequest $request)
    {
        $user = $request->user();
        $planId = $request->input('plan_id');
        $plan = $this->planRepository->find($planId);

        if (!$plan) {
            return Response::apiResponse(
                HttpStatus::NOT_FOUND, 
                null, 
                'Plan not found'
            );
        }

        try {
            $checkoutUrl = $this->subscriptionService->createCheckoutSession($user, $plan);

            return Response::apiResponse(
                HttpStatus::OK,
                ['checkout_url' => $checkoutUrl],
                'Checkout session created successfully'
            );

        } catch (Exception $e) {
            return Response::apiResponse(
                HttpStatus::BAD_REQUEST, 
                null, 
                $e->getMessage()
            );
        }
    }

    /**
     * عرض حالة الاشتراك الحالي للمستخدم
     */
    public function currentSubscription(Request $request)
    {
        $subscription = $this->subscriptionService->getCurrentActiveSubscription($request->user()->id);

        if (!$subscription) {
            return Response::apiResponse(
                HttpStatus::NOT_FOUND, 
                null, 
                'No active subscription found.'
            );
        }

        $data = [
            'status' => $subscription->status->value,
            'plan_name' => $subscription->plan->name,
            'start_date' => $subscription->start_date,
            'end_date' => $subscription->end_date,
        ];
        
        return Response::apiResponse(
            HttpStatus::OK, 
            $data, 
            'Current subscription status retrieved.'
        );
    }

    public function paymentSuccess()
    {
        return response()->json(['message' => 'Payment success redirection. Check webhook for final status.']);
    }

    public function paymentCancel()
    {
        return response()->json(['message' => 'Payment cancelled.']);
    }
}
<?php
namespace App\Http\Controllers\Api;
use App\Http\Resources\UserResource;
use Exception;
use App\Enums\HttpStatus;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Response;
use App\Repositories\EloquentUserRepository;

class AdminController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService,
        protected EloquentUserRepository $userRepository
    )
    {
    }

    public function listUsers()
    {
        $users = $this->userRepository->all();

      return Response::apiResponse(
            HttpStatus::OK,
            UserResource::collection($users),
            'Available plans retrieved successfully'
        );

    }


    public function deactivateSubscription(int $subscriptionId)
    {
        try {
            $subscription = $this->subscriptionService->deactivateSubscription($subscriptionId);

            if (!$subscription) {
                return Response::apiResponse(
                    HttpStatus::NOT_FOUND, 
                    null, 
                    'Subscription not found.'
                );
            }

            return Response::apiResponse(
                HttpStatus::OK,
                ['status' => $subscription->status->value],
                'Subscription deactivated successfully.'
            );

        } catch (Exception $e) {
            return Response::apiResponse(
                HttpStatus::INTERNAL_SERVER_ERROR,
                null,
                'Error processing deactivation: ' . $e->getMessage()
            );
        }
    }
}
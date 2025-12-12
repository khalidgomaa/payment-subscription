<?php

namespace App\Providers;

use Stripe\Stripe;
use App\Enums\HttpStatus;
use App\Repositories\EloquentPlanRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use App\Repositories\EloquentUserRepository;
use App\Services\StripeWebhookService;
use App\Repositories\EloquentSubscriptionRepository;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\StripeWebhookServiceInterface;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PlanRepositoryInterface::class, EloquentPlanRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class, EloquentSubscriptionRepository::class);
        $this->app->bind(StripeWebhookServiceInterface::class, StripeWebhookService::class);
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
           Response::macro('apiResponse', function (HttpStatus $status, array|object|null $data = null, ?string $message = null, array $meta = [], array $messageParams = []) {
            if ($status === HttpStatus::NO_CONTENT) {
                return Response::json([], HttpStatus::NO_CONTENT->value);
            }

            $response = [
                'http_code' => $status,
                'code' => (int) !$status->isSuccess(),
                'message' => $message ?? $status->message(),
                'data' => $data,
                'meta' =>$meta,
            ];

            return Response::json($response, $status->value);
            });
    }
}

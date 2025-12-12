<?php

// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\StripeWebhookController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::prefix('subscriptions/payment')->group(function () {
    Route::get('success', [SubscriptionController::class, 'paymentSuccess']);
    Route::get('cancel', [SubscriptionController::class, 'paymentCancel']);
});

Route::post('stripe/webhook', StripeWebhookController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);

    Route::prefix('subscriptions')->group(function () {
        Route::get('plans', [SubscriptionController::class, 'listPlans']);
        Route::post('purchase', [SubscriptionController::class, 'purchase']);
        Route::get('current', [SubscriptionController::class, 'currentSubscription']);
    });
    Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
        Route::get('users', [AdminController::class, 'listUsers']);

        Route::post('subscriptions/{subscriptionId}/deactivate', [AdminController::class, 'deactivateSubscription']);
    });

});
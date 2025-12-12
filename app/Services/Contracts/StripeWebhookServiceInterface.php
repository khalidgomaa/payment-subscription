<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;

interface StripeWebhookServiceInterface
{
    public function handleWebhook(Request $request): bool;
}


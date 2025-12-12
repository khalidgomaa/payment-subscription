<?php

namespace App\Enums;

enum SubscriptionStatusEnum: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Expired = 'expired';
    case Canceled = 'canceled';
}

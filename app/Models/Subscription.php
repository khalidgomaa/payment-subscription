<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\SubscriptionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = ['user_id', 'plan_id', 'status', 'start_date', 'end_date', 'payment_id'];


    protected $casts = [
        'status' => SubscriptionStatusEnum::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', SubscriptionStatusEnum::Active)
                    ->where('end_date', '>', Carbon::now());
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan():BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}

<?php

namespace App\Models;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\PlanTypeEnum;

class Plan extends Model
{
    /** @use HasFactory<\Database\Factories\PlanFactory> */
    use HasFactory;

    protected $fillable = ['name', 'price','description', 'duration_days'];

    protected $casts = [
        'name' => PlanTypeEnum::class,
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}

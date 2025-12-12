<?php

namespace App\Repositories;

use App\Models\Plan;
use App\Repositories\Contracts\PlanRepositoryInterface;

class EloquentPlanRepository implements PlanRepositoryInterface
{
    public function all()
    {
        return Plan::all();
    }


    public function find(int $id): ?Plan
    {
        return Plan::find($id);
    }
}
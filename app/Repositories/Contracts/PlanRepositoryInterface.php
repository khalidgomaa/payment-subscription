<?php
namespace App\Repositories\Contracts;

use App\Models\Plan;


interface PlanRepositoryInterface
{
    public function all();
    public function find(int $id): ?Plan;
}
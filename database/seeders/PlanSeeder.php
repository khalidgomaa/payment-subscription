<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */public function run()
        {
            Plan::insert([
                ['name' => 'Basic', 'price' => 10.00, 'duration_days' => 30, 'description' => 'Basic plan with essential features.', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Pro', 'price' => 20.00, 'duration_days' => 30, 'description' => 'Pro plan with advanced features.', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Premium', 'price' => 50.00, 'duration_days' => 30, 'description' => 'Premium plan with all features.', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
}

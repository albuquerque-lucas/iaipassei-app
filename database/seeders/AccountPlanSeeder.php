<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AccountPlan;

class AccountPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AccountPlan::create([
            'access_level' => 1,
            'name' => 'Regular',
            'description' => 'Padrão para usuários cadastrados.',
            'price' => 0.00,
            'duration_days' => null,
            'is_public' => true,
        ]);

        AccountPlan::create([
            'access_level' => 10,
            'name' => 'Admin',
            'description' => 'Apenas para administradores',
            'price' => 0.00,
            'duration_days' => null,
            'is_public' => false,
        ]);
    }
}

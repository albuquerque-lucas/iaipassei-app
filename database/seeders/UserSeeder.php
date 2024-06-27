<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'account_plan_id' => 2,
            'first_name' => 'Lucas',
            'last_name' => 'Albuquerque',
            'username' => 'lucaslpra',
            'email' => 'lucaslpra@gmail.com',
            'phone_number' => '32988673808',
            'password' => Hash::make('12345678'),
            'sex' => 'Masculino',
            'sexual_orientation' => 'Heterossexual',
            'gender' => 'Homem Cis',
            'race' => 'Branco',
            'disability' => 'Nenhuma',
            'email_verified_at' => now(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;

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
            'username' => 'albuquerque.lucas',
            'email' => 'lucaslpra@gmail.com',
            'phone_number' => '32988673808',
            'password' => Hash::make('123123123'),
            'sex' => 'Masculino',
            'sexual_orientation' => 'Heterossexual',
            'gender' => 'Homem Cis',
            'race' => 'Branco',
            'disability' => 'Nenhuma',
            'email_verified_at' => now(),
        ]);

        User::create([
            'account_plan_id' => 2,
            'first_name' => 'Yuri',
            'last_name' => 'Duarte',
            'username' => 'duarte.yuri',
            'email' => 'yuri.duarte@example.com', // Gerando um email fictício
            'phone_number' => '32988673809',
            'password' => Hash::make('12345678'),
            'sex' => 'Masculino',
            'sexual_orientation' => 'Heterossexual',
            'gender' => 'Homem Cis',
            'race' => 'Branco',
            'disability' => 'Nenhuma',
            'email_verified_at' => now(),
        ]);

        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            User::create([
                'account_plan_id' => 1,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'username' => $faker->userName,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => $faker->unique()->phoneNumber,
                'password' => Hash::make('123123123'),
                'sex' => 'Masculino',
                'sexual_orientation' => 'Heterossexual',
                'gender' => 'Homem Cis',
                'race' => 'Branco',
                'disability' => 'Nenhuma',
                'email_verified_at' => now(),
            ]);
        }
    }
}

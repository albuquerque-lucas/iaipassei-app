<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Examination;

class UserExaminationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar o último Examination adicionado
        $lastExamination = Examination::latest('id')->first();

        if ($lastExamination) {
            // Buscar todos os usuários exceto aquele com username 'albuquerque.lucas'
            $users = User::where('username', '!=', 'albuquerque.lucas')->get();

            // Associar cada usuário ao último Examination
            foreach ($users as $user) {
                $user->examinations()->attach($lastExamination->id);
            }
        }
    }
}

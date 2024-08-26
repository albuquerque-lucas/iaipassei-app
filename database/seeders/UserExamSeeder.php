<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Examination;
use App\Models\Exam;

class UserExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar todos os usuários exceto o que tem username 'albuquerque.lucas'
        $users = User::where('username', '!=', 'albuquerque.lucas')->get();

        foreach ($users as $user) {
            // Buscar o último Examination associado ao usuário
            $lastExamination = $user->examinations()->latest('id')->first();

            if ($lastExamination) {
                // Buscar o último Exam associado ao Examination
                $lastExam = $lastExamination->exams()->latest('id')->first();

                if ($lastExam) {
                    // Associar o usuário ao último Exam
                    $user->exams()->attach($lastExam->id);
                }
            }
        }
    }
}

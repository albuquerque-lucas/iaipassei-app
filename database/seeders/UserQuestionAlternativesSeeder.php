<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Examination;
use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Support\Facades\DB;

class UserQuestionAlternativesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar o último Examination
        $lastExamination = Examination::latest('id')->first();

        if ($lastExamination) {
            // Buscar o último Exam associado ao Examination
            $lastExam = $lastExamination->exams()->latest('id')->first();

            if ($lastExam) {
                // Buscar todos os usuários que não têm o username "albuquerque.lucas"
                $users = User::where('username', '!=', 'albuquerque.lucas')->get();

                // Buscar todas as questões do Exam
                $questions = $lastExam->examQuestions;

                // Associar cada usuário a uma alternativa aleatória de cada questão
                foreach ($users as $user) {
                    foreach ($questions as $question) {
                        // Escolher uma alternativa aleatória (de 'a' a 'e')
                        $randomAlternative = $question->alternatives()->inRandomOrder()->first();

                        if ($randomAlternative) {
                            // Inserir o relacionamento na tabela pivot
                            DB::table('user_question_alternatives')->insert([
                                'user_id' => $user->id,
                                'exam_question_id' => $question->id,
                                'question_alternative_id' => $randomAlternative->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }

    }
}

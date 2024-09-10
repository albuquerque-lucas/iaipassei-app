<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
        $excludedUsernames = ['albuquerque.lucas', 'duarte.yuri', 'varao.matheus'];
        $users = User::whereNotIn('username', $excludedUsernames)->take(100)->get();

        $groupA = $users->slice(0, 30);
        $groupB = $users->slice(30, 20);
        $groupC = $users->slice(50, 21);
        $groupD = $users->slice(71, 19);
        $groupE = $users->slice(90, 10);

        $this->assignAlternativesToGroup($groupA, 'a');
        $this->assignAlternativesToGroup($groupB, 'b');
        $this->assignAlternativesToGroup($groupC, 'c');
        $this->assignAlternativesToGroup($groupD, 'd');
        $this->assignAlternativesToGroup($groupE, 'e');
    }

    /**
     * Assign a specific alternative to a group of users
     */
    private function assignAlternativesToGroup($users, $letter)
    {
        foreach ($users as $user) {
            // Buscar o último Exam associado ao usuário
            $lastExam = $user->exams()->latest('id')->first();

            if ($lastExam) {
                // Buscar todas as questões do Exam
                $questions = $lastExam->examQuestions;

                foreach ($questions as $question) {
                    // Buscar a alternativa específica para a letra fornecida
                    $alternative = $question->alternatives()->where('letter', $letter)->first();

                    if ($alternative) {
                        // Inserir o relacionamento na tabela pivot
                        DB::table('user_question_alternatives')->insert([
                            'user_id' => $user->id,
                            'exam_question_id' => $question->id,
                            'question_alternative_id' => $alternative->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Examination;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\QuestionAlternative;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $examinations = Examination::all();

        foreach ($examinations as $examination) {
            $exam = Exam::create([
                'examination_id' => $examination->id,
                'title' => 'Prova do ' . $examination->title,
                'description' => 'Descrição da prova do ' . $examination->title,
                'date' => now(),
            ]);

            for ($i = 1; $i <= 60; $i++) {
                $examQuestion = ExamQuestion::create([
                    'exam_id' => $exam->id,
                    'question_number' => $i,
                ]);

                foreach (range('A', 'E') as $letter) {
                    QuestionAlternative::create([
                        'exam_question_id' => $examQuestion->id,
                        'letter' => $letter,
                    ]);
                }
            }
        }
    }
}

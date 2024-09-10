<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Examination;
use App\Models\Exam;

class UserExamSeeder extends Seeder
{
    public function run(): void
    {
        $excludedUsernames = ['albuquerque.lucas', 'duarte.yuri', 'varao.matheus'];
        $users = User::whereNotIn('username', $excludedUsernames)->get();

        foreach ($users as $user) {
            $lastExamination = $user->examinations()->latest('id')->first();

            if ($lastExamination) {
                $lastExam = $lastExamination->exams()->latest('id')->first();

                if ($lastExam) {
                    $user->exams()->attach($lastExam->id);
                }
            }
        }
    }
}

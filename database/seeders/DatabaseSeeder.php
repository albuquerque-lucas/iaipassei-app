<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AccountPlanSeeder::class,
            StudyAreaSeeder::class,
            UserSeeder::class,
            EducationLevelSeeder::class,
            ExaminationSeeder::class,
            ExamSeeder::class,
            SubjectSeeder::class,
            UserQuestionAlternativesSeeder::class,
        ]);
    }
}

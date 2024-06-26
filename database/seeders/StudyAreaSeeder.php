<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudyArea;
use App\Models\Subject;

class StudyAreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studyAreas = config('study_areas');

        foreach ($studyAreas as $area => $subjects) {
            $studyArea = StudyArea::create(['name' => $area]);

            foreach ($subjects as $subject) {
                Subject::create([
                    'study_area_id' => $studyArea->id,
                    'title' => $subject,
                ]);
            }
        }
    }
}

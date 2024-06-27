<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudyArea;

class StudyAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studyAreas = config('study_areas');

        foreach ($studyAreas as $area) {
            $studyArea = StudyArea::create(['name' => $area]);
        }
    }
}

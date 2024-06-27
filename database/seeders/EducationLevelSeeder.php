<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EducationLevel;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $educationLevels = [
            'Ensino Fundamental',
            'Ensino Médio',
            'Ensino Técnico',
            'Ensino Superior',
            'Pós-Graduação',
            'Mestrado',
            'Doutorado',
        ];

        foreach ($educationLevels as $level) {
            EducationLevel::create(['name' => $level]);
        }
    }
}

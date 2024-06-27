<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Examination; // Certifique-se de importar a classe Examination

class ExaminationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $examinations = config('examinations');

        foreach ($examinations as $examination) {
            Examination::create($examination);
        }
    }
}

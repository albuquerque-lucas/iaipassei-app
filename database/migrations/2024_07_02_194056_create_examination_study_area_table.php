<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('examination_study_area', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examination_id')->constrained()->onDelete('cascade');
            $table->foreignId('study_area_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examinations_study_areas');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Topic;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Exam::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Subject::class)->nullable()->constrained()->onDelete('set null');
            $table->foreignIdFor(Topic::class)->nullable()->constrained()->onDelete('set null');
            $table->integer('question_number');
            $table->text('statement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Examination;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Examination::class)->cascadeOnDelete();
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('extension')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};

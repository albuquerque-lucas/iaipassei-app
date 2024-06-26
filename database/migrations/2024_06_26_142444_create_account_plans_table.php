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
        Schema::create('account_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('access_level');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('description')->nullable();
            $table->integer('duration_days')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_plans');
    }
};

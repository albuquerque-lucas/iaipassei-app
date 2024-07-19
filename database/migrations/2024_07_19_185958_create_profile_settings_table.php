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
        Schema::create('profile_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('show_username')->default(false);
            $table->boolean('show_email')->default(false);
            $table->boolean('show_sex')->default(false);
            $table->boolean('show_sexual_orientation')->default(false);
            $table->boolean('show_gender')->default(false);
            $table->boolean('show_race')->default(false);
            $table->boolean('show_disability')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_settings');
    }
};

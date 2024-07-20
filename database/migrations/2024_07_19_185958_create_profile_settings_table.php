<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profile_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->boolean('show_username')->default(true);
            $table->boolean('show_email')->default(true);
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

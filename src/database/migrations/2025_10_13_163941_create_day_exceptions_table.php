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
        Schema::create('day_exceptions', function (Blueprint $table) {
            $table->id();
            $table->integer('went_workout');
            $table->date('date')->format('Y-m-d');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('workout_id')->references('id')->on('workouts');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_exceptions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');                  
            $table->string('image')->nullable();     
            $table->string('video_url')->nullable(); 
            $table->foreignId('workout_id')->constrained('workouts')->onDelete('cascade');
            $table->foreignId('muscle_id')->constrained('muscles')->onDelete('cascade');
            $table->boolean('status')->default(1);   
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};


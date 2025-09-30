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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('workout_id')->constrained('workouts')->onDelete('cascade');
            $table->foreignId('muscle_group_id')->constrained('muscle_groups')->onDelete('cascade');
            $table->foreignId('muscle_id')->nullable()->constrained('muscles')->onDelete('cascade');
            $table->boolean('status')->default(1);   
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};


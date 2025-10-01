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
        Schema::create('user_bodies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('r_biceps')->nullable();
            $table->integer('l_biceps')->nullable();
            $table->integer('r_forearm')->nullable();
            $table->integer('l_forearm')->nullable();
            $table->integer('chest')->nullable();
            $table->integer('waist')->nullable();
            $table->integer('pelvic_girdle')->nullable();
            $table->integer('r_thigh')->nullable();
            $table->integer('l_thigh')->nullable();
            $table->integer('r_shin')->nullable();
            $table->integer('l_shin')->nullable();
            $table->integer('shoulder_length')->nullable();
            $table->date('measurement_date')->default(now()->format('Y-m-d'));
            $table->boolean('status')->default(1);   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bodies');
    }
};

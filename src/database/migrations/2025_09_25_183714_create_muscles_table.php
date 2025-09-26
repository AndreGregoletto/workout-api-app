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
        Schema::create('muscles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->required();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('muscle_group_id')->constrained('muscle_groups', 'id');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muscles');
    }
};

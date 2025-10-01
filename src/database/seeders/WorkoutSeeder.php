<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Workout;

class WorkoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workout = Workout::create([
            'name' => 'PPL',
            'image' => null,
            'description' => 'Push, Pull, Legs',
            'cicle' => '2',
            'training_days' => '6',
            'no_training_days' => '1',
            'duration' => "12",
            'user_id' => 1,
            'status' => 1
        ]);
    }
}

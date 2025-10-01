<?php

namespace Database\Seeders;

use App\Models\WorkoutPivotet;
use Illuminate\Database\Seeder;

class WorkoutPivotetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workoutPivotets = [
            ['exercise_id' =>1,  'day_id' => 1, 'ordering' => 1],
            ['exercise_id' =>2,  'day_id' => 1, 'ordering' => 2],
            ['exercise_id' =>3,  'day_id' => 1, 'ordering' => 3],
            ['exercise_id' =>4,  'day_id' => 2, 'ordering' => 1],
            ['exercise_id' =>5,  'day_id' => 2, 'ordering' => 2],
            ['exercise_id' =>6,  'day_id' => 2, 'ordering' => 3],
            ['exercise_id' =>7,  'day_id' => 2, 'ordering' => 4],
            ['exercise_id' =>8,  'day_id' => 2, 'ordering' => 5],
            ['exercise_id' =>9,  'day_id' => 1, 'ordering' => 4],
            ['exercise_id' =>10, 'day_id' => 1, 'ordering' => 5],
            ['exercise_id' =>11, 'day_id' => 2, 'ordering' => 6],
            ['exercise_id' =>12, 'day_id' => 2, 'ordering' => 7],
            ['exercise_id' =>13, 'day_id' => 1, 'ordering' => 6],
            ['exercise_id' =>14, 'day_id' => 3, 'ordering' => 1],
            ['exercise_id' =>15, 'day_id' => 3, 'ordering' => 2],
            ['exercise_id' =>16, 'day_id' => 3, 'ordering' => 3],
            ['exercise_id' =>17, 'day_id' => 3, 'ordering' => 4],
            ['exercise_id' =>18, 'day_id' => 3, 'ordering' => 5],
            ['exercise_id' =>19, 'day_id' => 3, 'ordering' => 6],
            ['exercise_id' =>20, 'day_id' => 3, 'ordering' => 7],
            ['exercise_id' =>21, 'day_id' => 3, 'ordering' => 8],
        ];

        foreach ($workoutPivotets as $workoutPivotet) {
            $workoutPivotet['user_id'] = 1;
            $workoutPivotet['workout_id'] = 1;
            $workoutPivotet['status'] = 1;
            WorkoutPivotet::create($workoutPivotet);
        }
    }
}

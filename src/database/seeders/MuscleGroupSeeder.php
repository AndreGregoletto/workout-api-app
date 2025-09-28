<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MuscleGroup;

class MuscleGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $muscleGroups = [
            ['name' => 'Peito',  'description' => null, 'image' => null, 'status' => 1],
            ['name' => 'Costas', 'description' => null, 'image' => null, 'status' => 1],
            ['name' => 'Ombro',  'description' => null, 'image' => null, 'status' => 1],
            ['name' => 'Braços', 'description' => null, 'image' => null, 'status' => 1],
            ['name' => 'Pernas', 'description' => null, 'image' => null, 'status' => 1],
            ['name' => 'Core',   'description' => null, 'image' => null, 'status' => 1],
        ];

        foreach ($muscleGroups as $group) {
            MuscleGroup::firstOrCreate(
                ['name' => $group['name']],
                $group
            );
        }
    }
}

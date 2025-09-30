<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exercise;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exercise = [
            ['name' => 'Supino Reto com barra',         'workout_id' => 1, 'muscle_group_id' => '1'],
            ['name' => 'Supino Inclinado com Halteres', 'workout_id' => 1, 'muscle_group_id' => '1'],
            ['name' => 'Fly Máquina',                   'workout_id' => 1, 'muscle_group_id' => '1'],
            ['name' => 'Levantamento Terra',            'workout_id' => 1, 'muscle_group_id' => '2'],
            ['name' => 'Barra fixa',                    'workout_id' => 1, 'muscle_group_id' => '2'],
            ['name' => 'Remada Curvada',                'workout_id' => 1, 'muscle_group_id' => '2'],
            ['name' => 'Puxador Unilateral Polia',      'workout_id' => 1, 'muscle_group_id' => '2'],
            ['name' => 'Face Pull',                     'workout_id' => 1, 'muscle_group_id' => '2'],
            ['name' => 'Desenvolvimento MIlitar',       'workout_id' => 1, 'muscle_group_id' => '3'],
            ['name' => 'Elevação Lateral com Halteres', 'workout_id' => 1, 'muscle_group_id' => '3'],
            ['name' => 'Elevação Frontal com Halteres', 'workout_id' => 1, 'muscle_group_id' => '3'],
            ['name' => 'Rosca martelo polia com corda', 'workout_id' => 1, 'muscle_group_id' => '4'],
            ['name' => 'Rosca concentrada polia baixa', 'workout_id' => 1, 'muscle_group_id' => '4'],
            ['name' => 'Barra paralela',                'workout_id' => 1, 'muscle_group_id' => '4'],
            ['name' => 'Agachamento Livre',             'workout_id' => 1, 'muscle_group_id' => '5'],
            ['name' => 'Bulgaro Livre',                 'workout_id' => 1, 'muscle_group_id' => '5'],
            ['name' => 'Stiff',                         'workout_id' => 1, 'muscle_group_id' => '5'],
            ['name' => 'Legpress 45°',                  'workout_id' => 1, 'muscle_group_id' => '5'],
            ['name' => 'Cadeira Extensora',             'workout_id' => 1, 'muscle_group_id' => '5'],
            ['name' => 'Cadeira Flexora',               'workout_id' => 1, 'muscle_group_id' => '5'],
            ['name' => 'Panturilha em pé',              'workout_id' => 1, 'muscle_group_id' => '5'],
        ];

        foreach ($exercise as $value) {
            $value['image']     = null;
            $value['video_url'] = null;
            $value['user_id']   = 1;
            $value['muscle_id'] = null;
            $value['status']    = 1;

            Exercise::firstOrCreate($value);
        }
    }
}

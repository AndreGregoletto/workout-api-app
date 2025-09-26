<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MuscleGroup;
use App\Models\Muscle;

class MusclesSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            'Peito' => [
                'Peitoral maior',
                'Peitoral menor',
                'Serrátil anterior',
            ],
            'Costas' => [
                'Latíssimo do dorso',
                'Trapézio superior',
                'Trapézio médio',
                'Trapézio inferior',
                'Rombóides',
                'Eretor da espinha',
                'Redondo maior',
                'Redondo menor',
                'Infraespinhal',
            ],
            'Ombro' => [
                'Deltoide anterior',
                'Deltoide medial',
                'Deltoide posterior',
                'Supraespinhal',
                'Infraespinhal',
                'Subescapular',
                'Redondo menor',
            ],
            'Braços' => [
                'Bíceps braquial',
                'Braquial',
                'Braquiorradial',
                'Tríceps cabeça longa',
                'Tríceps cabeça lateral',
                'Tríceps cabeça medial',
                'Flexores do antebraço',
                'Extensores do antebraço',
            ],
            'Pernas' => [
                'Reto femoral',
                'Vasto lateral',
                'Vasto medial',
                'Vasto intermédio',
                'Bíceps femoral',
                'Semitendíneo',
                'Semimembranáceo',
                'Glúteo máximo',
                'Glúteo médio',
                'Glúteo mínimo',
                'Gastrocnêmio',
                'Sóleo',
            ],
            'Core' => [
                'Reto abdominal',
                'Oblíquo externo',
                'Oblíquo interno',
                'Transverso do abdômen',
            ],
        ];

        foreach ($groups as $groupName => $muscles) {
            $group = MuscleGroup::firstOrCreate(
                ['name' => $groupName],
                ['description' => null, 'image' => null, 'status' => 1]
            );

            foreach ($muscles as $muscleName) {
                Muscle::firstOrCreate(
                    [
                        'name' => $muscleName,
                        'muscle_group_id' => $group->id
                    ],
                    [
                        'description' => null,
                        'image' => null,
                        'status' => 1
                    ]
                );
            }
        }
    }
}
            // // Peito
            // ['name' => 'Supino Reto com Barra',              'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Supino Reto com halteres',           'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Supino Reto Máquina',                'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Supino inclinado Barra',             'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Supino inclinado com halteres 30°',  'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Supino inclinado com halteres 45°',  'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Supino inclinado com halteres 60°',  'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Supino inclinado com halteres 75°',  'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Supino Declinado Barra',             'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Supino Declinado halteres',          'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Supino Declinado Smith',             'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Crucifixo Máquina',                  'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Crucifixo halteres',                 'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Crucifixo Polia',                    'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Cross polia Alta',                   'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Cross polia Média',                  'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            // ['name' => 'Cross polia Baixa',                  'description' => null, 'image' => null, 'muscle_group_id' => 1, 'status' => 1],
            
            // // Costas
            // ['name' => 'Leantamento Terra',                  'description' => null, 'image' => null, 'muscle_group_id' => 2, 'status' => 1],
            // ['name' => 'Barra fixa',                  'description' => null, 'image' => null, 'muscle_group_id' => 2, 'status' => 1],
            // ['name' => 'Remada curvada barra livre',                  'description' => null, 'image' => null, 'muscle_group_id' => 2, 'status' => 1],
            // ['name' => 'Remada curvada Máquina',                  'description' => null, 'image' => null, 'muscle_group_id' => 2, 'status' => 1],
            // ['name' => 'Remada cavalinho',                  'description' => null, 'image' => null, 'muscle_group_id' => 2, 'status' => 1],
            // ['name' => 'Remada cavalinho',                  'description' => null, 'image' => null, 'muscle_group_id' => 2, 'status' => 1],
            // ['name' => 'Remada alta aberta',                  'description' => null, 'image' => null, 'muscle_group_id' => 2, 'status' => 1],
            // ['name' => 'Remada alta fechada',                  'description' => null, 'image' => null, 'muscle_group_id' => 2, 'status' => 1],
            // ['name' => 'Puxador alto',                  'description' => null, 'image' => null, 'muscle_group_id' => 2, 'status' => 1],
            // ['name' => 'Puxador baixo',                  'description' => null, 'image' => null, 'muscle_group_id' => 2, 'status' => 1],
            // ['name' => 'Remada com halter',                  'description' => null, 'image' => null, 'muscle_group_id' => 2, 'status' => 1],
            // ['name' => 'Remada testa',                  'description' => null, 'image' => null, 'muscle_group_id' => 2, 'status' => 1],
            // ['name' => 'face pull',                  'description' => null, 'image' => null, 'muscle_group_id' => 2, 'status' => 1],
            // ['name' => 'face pull',                  'description' => null, 'image' => null, 'muscle_group_id' => 2, 'status' => 1],
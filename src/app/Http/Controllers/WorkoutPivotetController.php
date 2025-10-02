<?php

namespace App\Http\Controllers;

use App\Http\Requests\Grouper\CreateRequest;
use App\Http\Requests\Grouper\UpdateRequest;
use App\Models\WorkoutPivotet;
use Illuminate\Support\Facades\Auth;

class WorkoutPivotetController extends Controller
{
    private $exercise;
    private $workout;
    private $muscle;
    private $muscleGroup;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ExerciseController     $exercise,
        WorkoutController      $workout,
        MuscleController       $muscle,
        MuscleGroupsController $muscleGroup,
    )
    {
        $this->exercise    = $exercise;
        $this->workout     = $workout;
        $this->muscle      = $muscle;
        $this->muscleGroup = $muscleGroup;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $oWorkout = $this->workout->index();
            if($oWorkout->getStatusCode() != 200){
                throw new \Exception("Nenhum Treino encontrado", 1);
            }
            $oWorkout = json_decode($oWorkout->getContent());

            $workoutPivotet = WorkoutPivotet::select('workout_id', 'day_id', 'ordering', 'exercise_id')
                ->with([
                    'exercise' => function ($query) {
                        $query->select('id', 'name', 'image', 'video_url');
                    }
                ])->where('user_id', Auth::user()->id)
                ->where('workout_id', $oWorkout->id)
                ->where('status', 1)
                ->orderBy('day_id', 'asc')
                ->orderBy('ordering', 'asc')
                ->get();

            $response = [
                'id'          => $oWorkout->id,
                'name'        => $oWorkout->name,
                'description' => $oWorkout->description,
                'image'       => $oWorkout->image,
                'cicle'       => $oWorkout->cicle,
                'duration'    => $oWorkout->duration,
            ];

            if($workoutPivotet){
                $workoutPivotet->map(function ($item) use (&$response) {
                    $response['exercises'][$item->day_id][] = $item?->exercise;
                });
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            $aExercise = $this->exercise->index();
            
            if($aExercise->getStatusCode() != 200){
                throw new \Exception("Nenhum Exercicio encontrado", 1);
            }

            $aWorkout = $this->workout->index();

            if($aWorkout->getStatusCode() != 200){
                throw new \Exception("Nenhum Treino encontrado", 1);
            }

            $aExercise = json_decode($aExercise->getContent());
            $aWorkout  = json_decode($aWorkout->getContent());

            $response = ['exercise' => $aExercise, 'workout' => $aWorkout];
            
            return response()->json($response, 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        try {
            $aData = $request->all();
            $aData['ordering'] = $aData['ordering'] ?? null;
            $aData['user_id']  = Auth::user()->id;

            if($this->exercise->show($aData['exercise_id'])->getStatusCode() != 200){
                throw new \Exception("Nenhum Exercicio encontrado", 1);
            }

            if($this->workout->show($aData['workout_id'])->getStatusCode() != 200){
                throw new \Exception("Nenhum Treino encontrado", 1);    
            }

            $workoutPivotet = WorkoutPivotet::where('user_id', $aData['user_id'])
                ->where('workout_id', $aData['workout_id'])
                ->where('day_id', $aData['day_id'])
                ->where('ordering', $aData['ordering'])
                ->where('status', 1)
                ->first();

            if($workoutPivotet){
                throw new \Exception("Posição já ocupada", 1);
            }

            if(empty($aData['ordering']) ){
                $orderWorkout = WorkoutPivotet::where('user_id', $aData['user_id'])
                    ->where('workout_id', $aData['workout_id'])
                    ->where('day_id', $aData['day_id'])
                    ->where('status', 1)
                    ->orderBy('ordering', 'asc')
                    ->get();
                
                $order = 1;

                if($orderWorkout->count() > 0){
                    foreach ($orderWorkout as $key => $value) {
                        if($value->ordering != $order){
                            break;
                        }
                        $order++;
                    }
                }

                $aData['ordering'] = $order;
            }

            if(!WorkoutPivotet::create($aData)){
                throw new \Exception("Erro ao criar exercicio", 1);
            }

            return response()->json(['message' => 'Exercicio criado com sucesso'], 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $oWorkout = $this->workout->index();
        if($oWorkout->getStatusCode() != 200){
            throw new \Exception("Nenhum Treino encontrado", 1);    
        }

        $oWorkout = json_decode($oWorkout->getContent());

        $workoutPivotet = WorkoutPivotet::with([
            'exercise' => function ($query) {
                $query->select('id', 'name', 'image', 'video_url')
                    ->with([
                        'muscle' => function ($query) {
                            $query->select('id', 'name');
                        }, 'muscleGroup' => function ($query) {
                            $query->select('id', 'name');
                        }
                    ])
                    ->whereStatus(1);
            }])->where('user_id', Auth::user()->id)
            ->where('workout_id', $oWorkout->id)
            ->where('day_id', $id)
            ->where('status', 1)
            ->orderBy('ordering', 'asc')
            ->get();

        $response = [
            'id'          => $oWorkout->id,
            'name'        => $oWorkout->name,
            'description' => $oWorkout->description
        ];

        if($workoutPivotet){
            $workoutPivotet->map(function ($item) use (&$response) {
                $group              = $item?->exercise;
                $group              = $group->toArray();
                $group['idPivotet'] = $item->id;
                $group['ordering']  = $item->ordering;
                
                $response['exercises'][$item->day_id][] = collect($group);
            });
        }

        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $oWorkout = $this->workout->index();
            if($oWorkout->getStatusCode() != 200){
                throw new \Exception("Nenhum Treino encontrado para esse exercicio", 1);    
            }

            $oExecise = $this->exercise->index();
            if($oExecise->getStatusCode() != 200){
                throw new \Exception("Nenhum Exercicio encontrado", 1);    
            }            

            $oMuscle = $this->muscle->index();
            if($oMuscle->getStatusCode() != 200){
                throw new \Exception("Nenhum musculo encontrado", 1);    
            }

            $oMuscleGroup = $this->muscleGroup->index();
            if($oMuscleGroup->getStatusCode() != 200){
                throw new \Exception("Nenhum Grupo Muscular encontrado", 1);    
            }   

            $oMuscle      = json_decode($oMuscle->getContent());
            $oExecise     = json_decode($oExecise->getContent());
            $oWorkout     = json_decode($oWorkout->getContent());
            $oMuscleGroup = json_decode($oMuscleGroup->getContent());

            $workoutPivotet = WorkoutPivotet::with([
                'exercise' => function ($query) {
                    $query->with([
                        'muscle' => function ($query) {
                            $query->select('id', 'name');
                        }, 'muscleGroup' => function ($query) {
                            $query->select('id', 'name');
                        }
                    ]);
                }])->where('user_id', Auth::user()->id)
                ->where('workout_id', $id)
                ->orderBy('ordering', 'asc')
                ->get();
            
            $response = [
                'muscle'         => $oMuscle,    
                'muscleGroup'    => $oMuscleGroup,    
                'execise'        => $oExecise,    
                'workoutPivotet' => $workoutPivotet,    
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        try {
            $aData = $request->all();
            $aData['user_id']  = Auth::user()->id;
            $aData['ordering'] = $aData['ordering'] ?? null;

            $userWorkout = WorkoutPivotet::where('id', $id)->first();
            
            if(!$userWorkout){
                throw new \Exception("Exercicio não encontrado", 1);
            }

            if($userWorkout->user_id != Auth::user()->id){
                throw new \Exception("Você não pode editar esse exercicio", 1);
            }

            $workoutPivotet = WorkoutPivotet::where('user_id', Auth::user()->id)
                ->where('workout_id', $data['workout_id'] ?? $userWorkout->workout_id)
                ->where('day_id', $aData['day_id'] ?? $userWorkout->day_id)
                ->where('ordering', $aData['ordering'] ?? $userWorkout->ordering)
                ->where('id', '!=', $id)
                ->where('status', 1)
                ->first();

            if($workoutPivotet){
                throw new \Exception("Posição já ocupada", 1);
            }

            if(empty($aData['ordering']) ){
                $orderWorkout = WorkoutPivotet::where('user_id', $aData['user_id'])
                    ->where('workout_id', $aData['workout_id'])
                    ->where('day_id', $aData['day_id'])
                    ->where('status', 1)
                    ->orderBy('ordering', 'asc')
                    ->get();
                
                $order = 1;

                if($orderWorkout->count() > 0){
                    foreach ($orderWorkout as $key => $value) {
                        if($value->ordering != $order){
                            break;
                        }
                        $order++;
                    }
                }

                $aData['ordering'] = $order;
            }

            if(!$userWorkout->update($aData)){
                throw new \Exception("Erro ao atualizar exercicio", 1);                
            }

            return response()->json(['message' => 'Exercicio atualizado com sucesso'], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $userWorkout = WorkoutPivotet::where('id', $id)->first();
            
            if(!$userWorkout){
                throw new \Exception("Exercicio não encontrado", 1);
            }

            if($userWorkout->user_id != Auth::user()->id){
                throw new \Exception("Você não pode inativar esse exercicio", 1);
            }

            $userWorkout->status = 0;
            if(!$userWorkout->save()){
                throw new \Exception("Erro ao inativar exercicio", 1);
            }
            
            return response()->json(['message' => 'Exercicio inativado com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function countDaysWorkout($id)
    {
        try {
            $days = WorkoutPivotet::where('workout_id', $id)
                ->where('status', 1)
                ->groupBy('day_id')
                ->get();

            if(is_null($days)){
                throw new \Exception("Treino nao encontrado", 1);
            }

            return response()->json(['days' => $days->count()], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

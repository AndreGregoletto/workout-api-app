<?php

namespace App\Http\Controllers;

use App\Models\WorkoutPivotet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class WorkoutPivotetController extends Controller
{
    private $exercise;
    private $workout;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ExerciseController $exercise,
        WorkoutController $workout,
    )
    {
        $this->exercise = $exercise;
        $this->workout = $workout;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(WorkoutPivotet::where('status', 1)->all());
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
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkoutPivotet $workoutPivotet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkoutPivotet $workoutPivotet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkoutPivotet $workoutPivotet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkoutPivotet $workoutPivotet)
    {
        //
    }
}

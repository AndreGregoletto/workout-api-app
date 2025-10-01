<?php

namespace App\Http\Controllers;

use App\Http\Requests\Workout\CreateRequest;
use App\Http\Requests\Workout\UpdateRequest;
use App\Models\Workout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Workout::select('id', 'name', 'description', 'image', 'cicle', 'duration')->whereStatus(1)->first());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        try{
            $activeWorkout = Workout::whereStatus(1)->first();

            if($activeWorkout){
                $activeWorkout->status = 0;
                $activeWorkout->save();
                $activeWorkout = " Seu treino anterior foi inativado.";
            }

            $aData = $request->all();
            $aData['user_id'] = $request->user_id ?? Auth::user()->id;  

            if(!Workout::create($aData)){
                throw new Exception("Erro ao criar treino", 1);
            }

        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        } 

        $activeWorkout = $activeWorkout ?? "";

        return response()->json(['message' => 'Treino criado com sucesso!'. $activeWorkout], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $workout = Workout::whereStatus(1)->find($id);

            if(is_null($workout)){
                throw new Exception("Treino não encontrado", 1);
            }

            return response()->json($workout);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $workout = Workout::find($id);

            if(is_null($workout)){
                throw new Exception("Treino não encontrado", 1);
            }

            return response()->json($workout);

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
            $workout = Workout::whereUserId(Auth::user()->id)->find($id);

            if(is_null($workout)){
                throw new Exception("Treino nao encontrado", 1);
            }

            $workout->update($request->all());

            return response()->json(['message' => 'Treino atualizado com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $workout = Workout::whereUserId(Auth::user()->id)->find($id);

            if(is_null($workout)){
                throw new Exception("Treino nao encontrado", 1);
            }

            $workout->status = 0;
            $workout->save();

            return response()->json(['message' => 'Treino inativado com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

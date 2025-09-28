<?php

namespace App\Http\Controllers;

use App\Http\Requests\Exercise\CreateRequest;
use App\Http\Requests\Exercise\UpdateRequest;
use Illuminate\Http\Request;
use App\Models\Exercise;
use App\Models\Workout;
use Illuminate\Support\Facades\Auth;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Exercise::select('id', 'name', 'image', 'video_url', 'workout_id', 'muscle_id')
            ->whereStatus(1)
            ->whereUserId(Auth::user()->id)
            ->get());
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
        try {
            $aData = $request->all();
            $workout = Workout::whereUserId(Auth::user()->id)->find($aData['workout_id']);
            $aData['user_id'] = $request->user_id ?? Auth::user()->id;

            if(!$workout){
                throw new \Exception("Treino nao encontrado", 1);
            }

            if(Exercise::whereNameAndStatus($aData['name'], 1)->first()){
                throw new \Exception("Ja existe um exercicio ativo com esse nome", 1);
            }

            if(!Exercise::create($aData)){
                throw new \Exception("Erro ao criar exercicio", 1);
            }
            
            return response()->json(['message' => 'Exercicio criado com sucesso'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $exercise = Exercise::select('id', 'name', 'image', 'video_url', 'workout_id', 'muscle_id')
                ->whereStatus(1)
                ->whereUserId(Auth::user()->id)
                ->find($id);
        
            if(is_null($exercise)){
                throw new \Exception("Nenhum Exercicio encontrado", 1);
            }

            return response()->json($exercise);

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
            $exercise = Exercise::whereUserId(Auth::user()->id)->find($id);
        
            if(is_null($exercise)){
                throw new \Exception("Nenhum Exercicio encontrado", 1);
            }

            return response()->json($exercise);

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
            $workout = Workout::whereUserId(Auth::user()->id)->find($aData['workout_id']);

            if(!$workout){
                throw new \Exception("Treino nao encontrado", 1);
            }

            $exercise = Exercise::whereUserId(Auth::user()->id)->find($id);

            if(is_null($exercise)){
                throw new \Exception("Exercicio nao encontrado", 1);
            }

            if(Exercise::whereNameAndStatus($aData['name'], 1)->where('id', '!=', $id)->first()){
                throw new \Exception("Ja existe um exercicio ativo com esse nome", 1);
            }

            $exercise->update($aData);

            return response()->json(['message' => 'Exercicio atualizado com sucesso'], 200);
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
            $exercise = Exercise::whereUserId(Auth::user()->id)->find($id);

            if(is_null($exercise)){
                throw new \Exception("Exercicio nao encontrado", 1);
            }

            $exercise->status = 0;
            $exercise->save();

            return response()->json(['message' => 'Exercicio inativado com sucesso'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}

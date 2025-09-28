<?php

namespace App\Http\Controllers;

use App\Http\Requests\MuscleGroups\CreateRequest;
use App\Http\Requests\MuscleGroups\UpdateRequest;
use App\Models\MuscleGroup;
use Exception;
use Illuminate\Support\Facades\Auth;

class MuscleGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(MuscleGroup::select('id', 'name', 'description', 'image')->whereStatus(1)->get());
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
            $muscle = $request->except('image');
        
            if(!MuscleGroup::create($muscle)){
                throw new Exception("Erro ao criar grupo muscular", 1);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Grupo muscular criado com sucesso'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $muscle = MuscleGroup::whereStatus(1)->find($id);

            if(is_null($muscle)){
                throw new Exception("Nenhum grupo muscular encontrado", 1);
            }

            return response()->json($muscle);

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
            $muscle = MuscleGroup::find($id);

            if(is_null($muscle)){
                throw new Exception("Nenhum grupo muscular encontrado", 1);
            }

            return response()->json($muscle);

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
            $name = $request->name ?? null;
            
            if(!is_null($name)){
                $name = MuscleGroup::whereName($name)->whereStatus(1)->where('id', '!=', $id)->first();
            }
    
            if($name){
                throw new Exception("Já existe um grupo muscular com esse nome", 1);
            }

            $muscle = MuscleGroup::find($id);

            if(is_null($muscle)){
                throw new Exception("Nenhum grupo muscular encontrado", 1);
            }

            $muscle->update($request->all());

            return response()->json(['message' => 'Grupo muscular atualizado com sucesso'], 200);

        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if(Auth::user()->admin != 1){
                throw new Exception("Você não tem permissão para realizar essa ação", 1);
            }

            $muscle = MuscleGroup::find($id);

            if(is_null($muscle)){
                throw new Exception("Nenhum grupo muscular encontrado", 1);
            }

            $muscle->status = 0;
            $muscle->save();

            return response()->json(['message' => 'Grupo muscular removido com sucesso'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }    
    }
}

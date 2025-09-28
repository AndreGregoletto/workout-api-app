<?php

namespace App\Http\Controllers;

use App\Http\Requests\Muscle\CreateRequest;
use App\Http\Requests\Muscle\UpdateRequest;
use App\Models\Muscle;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;


class MuscleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Muscle::select('id', 'name', 'description', 'image', 'muscle_group_id')->whereStatus(1)->get());
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
        
            if(!Muscle::create($muscle)){
                throw new Exception("Erro ao criar musculo", 1);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Musculo criado com sucesso'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $muscle = Muscle::whereStatus(1)->find($id);

            if(is_null($muscle)){
                throw new Exception("Nenhum musculo encontrado", 1);
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
        try{
            $muscle = Muscle::find($id);

            if(is_null($muscle)){
                throw new Exception("Nenhum musculo encontrado", 1);
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
                $name = Muscle::whereName($name)->whereStatus(1)->where('id', '!=', $id)->first();
            }

            if($name){
                throw new Exception("Já existe um musculo com esse nome", 1);
            }

            $muscle = Muscle::find($id);

            if(is_null($muscle)){
                throw new Exception("Nenhum musculo encontrado", 1);
            }

            $muscle->update($request->all());

            return response()->json(['message' => 'Musculo atualizado com sucesso'], 200);

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

            $muscle = Muscle::find($id);

            if(is_null($muscle)){
                throw new Exception("Nenhum musculo encontrado", 1);
            }

            $muscle->status = 0;
            $muscle->save();

            return response()->json(['message' => 'Musculo removido com sucesso'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

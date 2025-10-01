<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\Profile\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profile = User::select('id', 'name', 'social_name', 'date_birth')->where('status', 1)->paginate(10);

        return response()->json($profile);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $profile = User::select('name', 'social_name', 'date_birth')->find($id);

            if(is_null($profile)){
                throw new \Exception("Nenhum perfil encontrado", 1);
            }

            return response()->json($profile);
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
            if(Auth::user()->id != $id && Auth::user()->admin != 1){
                throw new \Exception("Nao autorizado", 1);
            }

            $profile = User::find($id);

            if(is_null($profile)){
                throw new \Exception("Nenhum perfil encontrado", 1);
            }

            return response()->json($profile);

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
            if(Auth::user()->id != $id && Auth::user()->admin != 1){
                throw new \Exception("Nao autorizado", 1);
            }

            $profile = User::find($id);

            if(is_null($profile)){
                throw new \Exception("Nenhum perfil encontrado", 1);
            }

            if(!$profile->update($request->all())){
                throw new \Exception("Nao foi possivel atualizar o perfil", 1);
            }

            return response()->json(['message' => 'Perfil atualizado com sucesso'], 200);

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
            if(Auth::user()->id != $id && Auth::user()->admin != 1){
                throw new \Exception("Nao autorizado", 1);
            }

            $profile = User::find($id);

            if(is_null($profile)){
                throw new \Exception("Nenhum perfil encontrado", 1);
            }

            $profile->status = 0;
            $profile->save();
            
            return response()->json(['message' => 'Perfil desativado com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

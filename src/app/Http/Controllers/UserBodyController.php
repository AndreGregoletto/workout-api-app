<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\Body\CreateRequest;
use App\Http\Requests\User\Body\UpdateRequest;
use App\Models\UserBody;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBodyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userBody = UserBody::whereUserId(Auth::user()->id)
            ->where('status', 1)
            ->orderBy('measurement_date', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json($userBody);
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

            if(empty($aData)){
                throw new \Exception("Nenhuma informação a ser registrada", 1);    
            }

            $aData['user_id'] = Auth::user()->id;
            
            if($aData['measurement_date'] > Carbon::now()->format('Y-m-d')){
                throw new \Exception("Mês inválido", 1);
            }

            $userBody = UserBody::where('user_id', $aData['user_id'])
                ->where('status', 1)
                ->where('measurement_date', $aData['measurement_date'])
                ->first();

            if($userBody){
                throw new \Exception("Já existe uma informação ativa para essa data", 1);
            }

            if(!UserBody::create($aData)){
                throw new \Exception("Erro ao registrar informação", 1);
            }

            return response()->json(['message' => 'Informação registrada com sucesso'], 200);

        } catch (\Exception $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $userBody = UserBody::whereUserId(Auth::user()->id)
                ->where('status', 1)
                ->where('id', $id)
                ->first();

            if(!$userBody){
                throw new \Exception("Informação não encontrada", 1);
            }

            $userBody = UserBody::whereUserId(Auth::user()->id)
                ->where('status', 1)
                ->where('id',  $id)
                ->first();

            return response()->json($userBody);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showFirst()
    {
        try {
            $userBody = UserBody::whereUserId(Auth::user()->id)
                ->where('status', 1)
                ->orderBy('measurement_date', 'desc')
                ->orderBy('updated_at', 'desc')
                ->first();

            if(!$userBody){
                throw new \Exception("Informação não encontrada", 1);
            }

            return response()->json($userBody);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        try {
            $userBody = UserBody::whereUserId(Auth::user()->id)->find($id);

            if(is_null($userBody)){
                throw new \Exception("Informação não encontrada", 1);
            }

            $data = $request->input('measurement_date', '');
            $userData = UserBody::whereUserId(Auth::user()->id)
                ->where('status', 1)
                ->where('measurement_date', $data)
                ->first();

            if($userData && $userData->id != $id){
                throw new \Exception("Já existe uma informação ativa para essa data", 1);
            }

            if(!$userBody->update($request->all())){
                throw new \Exception("Erro ao atualizar informação", 1);
            }

            return response()->json(['message' => 'Informação atualizada com sucesso'], 200);
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
            $userBody = UserBody::whereUserId(Auth::user()->id)->find($id);

            if(is_null($userBody)){
                throw new \Exception("Informação não encontrada", 1);
            }

            $userBody->status = 0;
            if(!$userBody->save()){
                throw new \Exception("Erro ao inativar informação", 1);
            }

            return response()->json(['message' => 'Registro inativado com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

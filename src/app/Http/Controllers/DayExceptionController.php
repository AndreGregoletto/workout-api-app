<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\DayException;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\DayException\CreateRequest;
use App\Http\Requests\User\DayException\UpdateRequest;

class DayExceptionController extends Controller
{
    private $workout;

    public function __construct(
        WorkoutController $workout
    )
    {
        $this->workout = $workout;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = DayException::whereStatus(1)
            ->whereUserId(Auth::user()->id)
            ->where('date', '>=', date('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get();
            
        return response()->json($response);
    }

    public function getByWorkout($id)
    {
        $response = [];
        try {
            if(empty($id)){
                throw new Exception("Treino inválido", 1);
            }
            $response = DayException::select('id', 'date', 'went_workout')
                ->whereStatus(1)
                ->whereUserId(Auth::user()->id)
                ->whereWorkoutId($id)
                ->where('date', '>=', date('Y-m-d'))
                ->orderBy('date', 'asc')
                ->get();

            if(empty($response)){
                throw new Exception("Nenhuma exceção encontrada", 1);
            }

            $i = 0;
            $count = '';
            $response = $response->mapWithKeys(function ($item) use (&$count, &$i) {
                $i = $item['date'] == $count ? $i + 1 : 1;
                $count = $item['date'];

                $aResponse[$item['date']] = [
                    'date'         => $item['date'],
                    'went_workout' => $item['went_workout'],
                    'count'        => $i
                ];
                return $aResponse;
            });

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
        return response()->json($response);
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
            $data            = $request->all();
            $data['user_id'] = Auth::user()->id;

            if($data['date'] < date("Y-m-d")){
                throw new Exception("Data inválida", 1);
            }

            if($this->workout->show($data['workout_id'])->getStatusCode() != 200){
                throw new Exception("Treino inválido", 1);
            }

            $dayException = DayException::where('user_id', $data['user_id'])
                ->where('date', $data['date'])
                ->where('status', 1);

            if($data['went_workout'] != 1 && $dayException->first()){
                throw new Exception("Existe uma exceção Ativa para essa data", 1);
            }

            if($data['went_workout'] == 1 && $dayException->where('went_workout', 0)->first()){
                throw new Exception("Existe uma exceção ativa", 1);
            }
            
            if(!DayException::create($data)){
                throw new Exception("Erro ao criar musculo", 1);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Exceção criada com sucesso'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $response = DayException::select('id', 'date', 'workout_id', 'went_workout')
                ->whereUserId(Auth::user()->id)
                ->whereStatus(1)
                ->find($id);
            return response()->json($response);
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
            $response = DayException::whereUserId(Auth::user()->id)->find($id);
            return response()->json($response);
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
            $data            = $request->all();
            $data['user_id'] = Auth::user()->id;
            $editException   = $this->edit($id);

            if($editException->getStatusCode() != 200){
                throw new Exception("Exceção nao encontrada", 1);
            }

            if($this->workout->show($data['workout_id'])->getStatusCode() != 200){
                throw new Exception("Treino inválido", 1);
            }
            
            $editException = json_decode($editException->getContent());
            $dayException  = DayException::where('user_id', $data['user_id'])
                ->where('date', $data['date'])
                ->where('id', '!=', $id)
                ->where('status', 1);

            if($data['went_workout'] != 1 && $dayException->first()){
                throw new Exception("Existe uma exceção Ativa para essa data", 1);
            }

            if($data['went_workout'] == 1 && $dayException->where('went_workout', 0)->first()){
                throw new Exception("Existe uma exceção ativa", 1);
            }

            if($editException->date < date("Y-m-d")){
                throw new Exception("Não é possivel editar uma exceção passada, apenas remover", 1);
            }

            $dayException = DayException::find($id);
            $dayException->update($data);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Exceção criada com sucesso'], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $find = $this->edit($id);
            if($find->getStatusCode() != 200){
                throw new Exception("Exceção nao encontrada", 1);
            }

            if(json_decode($find->getContent())->status == 0){
                throw new Exception("Nao é possivel remover uma exceção Inativa", 1);
            }

            $dayException = DayException::find($id);
            $dayException->status = 0;
            $dayException->save();
            return response()->json(['message' => 'Exceção removida com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\TodayRequest;
use App\Models\User;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $userBody;
    private $workout;
    private $workoutPivotet;

    public function __construct(
        UserBodyController       $userBody,
        WorkoutController        $workout,
        WorkoutPivotetController $workoutPivotet
    )
    {
        $this->userBody       = $userBody;
        $this->workout        = $workout;
        $this->workoutPivotet = $workoutPivotet;
    }

    public function index()
    {
        try {
            $user = User::select('id', 'name', 'social_name')
                ->with([
                    'workoutActive' => function ($query) {
                        $query->select('user_id', 'name', 'description', 'image', 'duration');
                    }
                ])
                ->find(Auth::user()->id);

            $request = new TodayRequest([
                'date' => date('Y-m-d')
            ]);            

            $workoutToday = $this->today($request);
            if($workoutToday->getStatusCode() == 200){
                $user->workoutActive[0]->workoutToday = $workoutToday->getData()->exercises;
            }

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function bodyActual()
    {
        return $this->userBody->showFirst();
    }

    public function workout()
    {
        try {
            $workout = $this->workout->index();
            if($workout->getStatusCode() != 200){
                throw new \Exception("Nao foi possivel carregar seu treino ativo", 1);
            }
            
            $oWorkout  = $workout->getData();
            $days      = $oWorkout->weekend == 1 ? 7 : 5;
            $days      = $days - $oWorkout->no_training_days ?? 0;
            $startDate = $oWorkout->date_start;
            $duration  = $oWorkout->duration;
            $endDate   = date("Y-m-d", strtotime("+$duration month", strtotime($startDate)));
            $bWeekend  = $oWorkout->weekend;

            $period = new DatePeriod(new DateTime($startDate), new DateInterval('P1D'), (new DateTime($endDate))->modify('+1 day'));
            $result = [];

            $countDaysWorkout = $this->workoutPivotet->countDaysWorkout($oWorkout->id);
            if($countDaysWorkout->getStatusCode() != 200){
                throw new \Exception("Nao foi possivel carregar seus exercicios ativo", 2);
            }
            
            $loop    = 1;
            $workout = [];
            $countDaysWorkout = $countDaysWorkout->getData()->days;
            while ($loop <= $countDaysWorkout) {
                $find = $this->workoutPivotet->show($loop);
                if($find->getStatusCode() != 200){
                    throw new \Exception("Nao foi possivel carregar seus exercicios ativo", 3);
                }
                $workout[$loop] = $find->getData()->exercises;
                $loop++;
            }
            
            $loop = 1;
            foreach ($period as $date) {
                $dayOfWeek = $date->format('N');
                $result[$date->format('Y-m-d')] = (!$bWeekend && ($dayOfWeek == 6 || $dayOfWeek == 7)) 
                    ? 0 
                    : $workout[$loop]->$loop ?? [];
                $loop++;
                $loop = $loop > $countDaysWorkout ? 1 : $loop;
            }

            return response()->json($result, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function today(TodayRequest $request)
    {
        try {
            $date = $request->input('date') ?? date("Y-m-d");
            if (!$date) {
                throw new \Exception("É necessário informar a data (Y-m-d).", 1);
            }

            $workout = $this->workout->index();
            if ($workout->getStatusCode() != 200) {
                throw new \Exception("Não foi possível carregar seu treino ativo", 2);
            }

            $oWorkout   = $workout->getData();
            $startDate  = new DateTime($oWorkout->date_start);
            $endDate    = new DateTime(date("Y-m-d", strtotime("+{$oWorkout->duration} month", strtotime($oWorkout->date_start))));
            $targetDate = new DateTime($date);
            if ($targetDate < $startDate || $targetDate > $endDate) {
                throw new \Exception("Data fora do período do treino", 2);
            }

            $countDaysWorkout = $this->workoutPivotet->countDaysWorkout($oWorkout->id);
            if ($countDaysWorkout->getStatusCode() != 200) {
                throw new \Exception("Não foi possível carregar seus exercícios ativos", 3);
            }
            $countDaysWorkout = $countDaysWorkout->getData()->days;

            $diffDays = $startDate->diff($targetDate)->days;
            $dayIndex = ($diffDays % $countDaysWorkout) + 1;

            $find = $this->workoutPivotet->show($dayIndex);
            if ($find->getStatusCode() != 200) {
                throw new \Exception("Não foi possível carregar seus exercícios ativos", 4);
            }

            $exercises = $find->getData()->exercises;

            return response()->json(['exercises'=> $exercises], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

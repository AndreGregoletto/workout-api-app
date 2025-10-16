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
    private $workout;
    private $userBody;
    private $dayException;
    private $workoutPivotet;

    public function __construct(
        WorkoutController        $workout,
        UserBodyController       $userBody,
        DayExceptionController   $dayException,
        WorkoutPivotetController $workoutPivotet,
    )
    {
        $this->workout        = $workout;
        $this->userBody       = $userBody;
        $this->dayException   = $dayException;
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

            $workout  = $this->workouts();
            if($workout->getStatusCode() == 200){
                $user->workoutActive[0]->workoutToday = $workout->getData();
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

            $exception  = $this->dayException->getByWorkout($oWorkout->id)->getData();
            $aException = [];
            if(!empty($exception)){
                foreach ($exception as $key => $value) {
                    $aException[$key] = [
                        'date'         => $value->date,
                        'went_workout' => $value->went_workout,
                        'count'        => $value->count
                    ];
                }
            }

            $loop = 1;
            foreach ($period as $date) {
                $key          = $date->format('Y-m-d');
                $dayOfWeek    = $date->format('N');

                if(!empty($aException)){
                    if(isset($aException[$key])){
                        if($aException[$key] == 0){
                            $result[$key] = 'no workout';
                            continue;
                        }
                        
                        if($aException[$key]['count'] > 1){
                            $a = 1;
                            while ($a <= $aException[$key]['count']) {
                                $result[$key][] = $workout[$loop]->$loop ?? [];
                                $loop++;
                                $loop = $loop > $countDaysWorkout ? 1 : $loop;
                                $a++;
                            }
                            continue;
                        }

                        $result[$key] = $workout[$loop]->$loop ?? [];
                        $loop++;
                        $loop = $loop > $countDaysWorkout ? 1 : $loop;
                        continue;
                    }
                }

                if((!$bWeekend && ($dayOfWeek == 6 || $dayOfWeek == 7))){
                    $result[$key] = 0;
                    continue;    
                }

                $result[$key] = $workout[$loop]->$loop ?? [];
                $loop++;
                $loop = $loop > $countDaysWorkout ? 1 : $loop;
            }
            
            return response()->json($result, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function workouts($date = null)
    {
        try {
            $workout = $this->workout();

            if($workout->getStatusCode() != 200){
                throw new \Exception("Nao foi possivel carregar seus treinos", 1);
            }
            $date    = $date ?? date("Y-m-d");
            $workout = $workout->getData();
            return response()->json($workout->$date);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function today(TodayRequest $request)
    {
        try {
            $date      = $request->input('date') ?? date("Y-m-d");
            $workout   = $this->workouts($date);
            $exercises = $workout->getStatusCode() == 200 ? $workout->getData() : [];

            return response()->json(['exercises'=> $exercises], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

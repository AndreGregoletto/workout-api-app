<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $userBody;

    public function __construct(
        UserBodyController $userBody
    )
    {
        $this->userBody = $userBody;
    }

    public function index()
    {
        $user = User::select('id', 'name')
            ->with([
                'workoutActive' => function ($query) {
                    $query->select('user_id', 'name', 'description', 'image', 'cicle', 'duration');
                }
            ])
            ->find(Auth::user()->id);

        return $user;
    }

    public function bodyActual()
    {
        return $this->userBody->showFirst();
    }
}

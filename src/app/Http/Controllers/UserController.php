<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
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
}

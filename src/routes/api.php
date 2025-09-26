<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\MuscleController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\MuscleGroupsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});


Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
    Route::post('/login',       'login')->name('login');
    Route::post('/register', 'register')->name('register');

});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/usuario', function () {
        return 'ok';
    });

    Route::group(['prefix' => 'user', 'controller' => UserController::class], function () {
        Route::get('/me', 'index')->name('user.me');
    });

    Route::resource('muscleGroups', MuscleGroupsController::class);
    Route::resource('muscle',       MuscleController::class);
    Route::resource('workout',      WorkoutController::class);
    Route::resource('exercise',     ExerciseController::class);
});

Route::get('notAuthorized', function () {
    return response()->json(['message' => 'notAuthorized'], 401);
})->name('notAuthorized');
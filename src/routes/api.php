<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DayExceptionController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\MuscleController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\MuscleGroupsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkoutPivotetController;
use App\Http\Controllers\UserBodyController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
    Route::post('/login',       'login')->name('login');
    Route::post('/register', 'register')->name('register');

    Route::get('/logout', 'logout')->middleware('auth:sanctum')->name('logout');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'user'], function () {
        Route::resource('body',         UserBodyController::class);
        Route::resource('profile',      ProfileController::class);
        Route::resource('dayException', DayExceptionController::class);

        Route::group(['controller' => UserController::class], function () {
            Route::get('/me',              'index')->name('user.me');
            Route::post('/today',          'today')->name('user.today');
            Route::get('/workout',       'workout')->name('user.workout');
            Route::get('/bodyActual', 'bodyActual')->name('user.bodyActual');
        });
    });

    Route::resource('muscleGroups', MuscleGroupsController::class);
    Route::resource('muscle',       MuscleController::class);
    Route::resource('workout',      WorkoutController::class);
    Route::resource('exercise',     ExerciseController::class);
    Route::resource('grouper',      WorkoutPivotetController::class);
});

Route::get('notAuthorized', function () {
    return response()->json(['message' => 'notAuthorized'], 401);
})->name('notAuthorized');
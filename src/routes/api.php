<?php

use App\Http\Controllers\AuthController;
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
    // Route::post('/register', 'logout')->name('register');

});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/usuario', function () {
        return 'ok';
    });

    Route::group(['prefix' => 'user', 'controller' => UserController::class], function () {
        Route::resource('body',    UserBodyController::class);
        Route::resource('profile', ProfileController::class);

        Route::get('/me',              'index')->name('user.me');
        Route::get('/bodyActual', 'bodyActual')->name('user.bodyActual');
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
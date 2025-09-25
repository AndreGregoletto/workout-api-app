<?php

use App\Http\Controllers\AuthController;
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

});

Route::get('notAuthorized', function () {
    return response()->json(['message' => 'notAuthorized'], 401);
})->name('notAuthorized');
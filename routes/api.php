<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// public routes 
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// protected routes 
Route::group(['middleware'=>'auth:sanctum'], function () {
    // user
    Route::get('/user', [AuthController::class,'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
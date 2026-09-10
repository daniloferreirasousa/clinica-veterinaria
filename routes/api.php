<?php

use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Endpoit aberto: Login e Emissão de Tokens
Route::post('/login', [AuthController::class, 'login']);

// Endpoints Protegidos por Bearer Token via Sanctum
Route::middleware('auth:sanctum')->name('api')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('animals', AnimalController::class)->parameters([
        'animals' => 'animal',
    ]);
});

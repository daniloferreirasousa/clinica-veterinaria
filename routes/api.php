<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Endpoit aberto: Login e Emissão de Tokens
Route::post('/login', [AuthController::class, 'login']);

// Endpoints Protegidos por Bearer Token via Sanctum
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
});

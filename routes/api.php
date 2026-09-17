<?php

use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\RaceController;
use App\Http\Controllers\Api\SpecieController;
use Illuminate\Support\Facades\Route;

// Endpoit aberto: Login e Emissão de Tokens
Route::post('/login', [AuthController::class, 'login']);

// Endpoints Protegidos por Bearer Token via Sanctum
Route::middleware('auth:sanctum')->name('api')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::apiResource('animals', AnimalController::class)->parameters([
        'animals' => 'animal',
    ]);

    Route::apiResource('races', RaceController::class)->parameters([
        'races' => 'race',
    ]);

    Route::apiResource('species', SpecieController::class)->parameters([
        'species' => 'specie'
    ]);

    Route::apiResource('consultations', ConsultationController::class)->parameters([
        'consultations' => 'consultatino'
    ]);

    Route::get('/consultations/options-create', [ConsultationController::class, 'optionsCreate'])->name('options');

    Route::get('/consultations/options-edit', [ConsultationController::class, 'optionsEdit'])->name('options-edit');

    Route::apiResource('exams', ExamController::class)->parameters([
        'exams' => 'exam'
    ]);

    Route::get('/exam/options-create', [ExamController::class, 'optionsCreate'])->name('options-create');

    Route::get('/exams/options-edit', [ExamController::class, 'optionsEdit'])->name('options-edit');
});

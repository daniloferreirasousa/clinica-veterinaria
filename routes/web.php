<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\SpecieController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DropdownController;


// Redirecionamentos da raiz
Route::get('/', function () {
    return redirect()->route('login');
});


// Rotas para visitante (não autenticados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


// Rotas protedidas (autenticados e com conta ativa)
Route::middleware(['auth', 'user.active'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');    

    Route::get('/dashboard',  [DashboardController::class, 'index'])
        ->name('dashboard');

    // Rotas para AJAX/Fetch API interna
    Route::prefix('api-local')->group(function() {
        Route::get('tutors/{tutor}/animals', [DropdownController::class, 'animalsByTutor'])->name('api.animals');
        Route::get('species/{specie}/races', [DropdownController::class, 'racesBySpecie'])->name('api.races');
    });

    // CRUD de usuários
    Route::middleware(['role:admin'])->group(function() {
        Route::resource('users', UserController::class)->parameters([
            'users' => 'user',
        ]);
    });

    Route::resource('tutors', TutorController::class)->parameters([
        'tutors' => 'tutor',
    ]);

    Route::resource('animals', AnimalController::class)->parameters([
        'animals' => 'animal',
    ]);

    Route::resource('species', SpecieController::class)->parameters([
        'species' => 'specie',
    ]);

    Route::resource('races', RaceController::class)->parameters([
        'races' => 'race'
    ]);

    Route::resource('consultations', ConsultationController::class)->parameters([
        'consultations' => 'consultation'
    ]);
   
});


// Rotas de Autenticação
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
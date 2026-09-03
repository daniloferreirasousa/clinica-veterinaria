<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\Api\DropdownController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SpecieController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VaccinationController;
use Illuminate\Support\Facades\Route;

// Redirecionamentos da raiz
Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas para visitante (não autenticados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

// Rotas protegidas (autenticados e com conta ativa)
Route::middleware(['auth', 'user.active'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');    

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    Route::resource('exams', ExamController::class)->parameters([
        'exams' => 'exam'
    ]);

    // Rotas para AJAX/Fetch API interna
    Route::prefix('api-local')->group(function () {
        Route::get('/tutors/{tutor}/animals', [DropdownController::class, 'animalsByTutor'])->name('api.animals');
        Route::get('/species/{specie}/races', [DropdownController::class, 'racesBySpecie'])->name('api.races');
    });

    // Rotas para Relatórios
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');

    // Rotas para Vacinas e Receitas
    Route::middleware(['role:veterinario,admin'])->group(function () {
        Route::resource('vaccinations', VaccinationController::class)->only(['index', 'create', 'store']);
        Route::resource('prescriptions', PrescriptionController::class)->only(['index', 'create', 'store', 'show']);
    });

    // Rotas para Histórico do Paciente
    Route::get('/animals/{animal}/history', [HistoryController::class, 'show'])->name('animals.history');
});
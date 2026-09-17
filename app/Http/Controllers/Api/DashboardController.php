<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Consultation;
use App\Models\Specie;
use App\Models\Tutor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
         // 1. Indicadores Principais
        $totalTutors = Tutor::count();
        $totalAnimals = Animal::count();
        $todayAppointments = Consultation::whereDate('date_time', Carbon::today())->count();
        $monthRevenue = Consultation::whereMonth('date_time', Carbon::now()->month)
            ->whereYear('date_time', Carbon::now()->year)
            ->where('status', 'concluida')
            ->sum('value');

        // Tabela de próximas Consultas do Dia Atual (max 5 registros)
        $consultationOfDay = Consultation::with(['animal.tutor', 'veterinarian'])
            ->whereDate('date_time', Carbon::today())
            ->whereIn('status', ['agendada', 'em_andamento'])
            ->orderBy('date_time', 'desc')
            ->take(5)
            ->get();

        // Gráfico 1: Volume de consultas dos últimos 6 meses
        $montlyAppointments = Consultation::select(
            DB::raw('MONTH(date_time) as mes'),
            DB::raw('YEAR(date_time) as ano'),
            DB::raw('count(*) as total')
        )
            ->where('date_time', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('ano', 'mes')
            ->orderBy('ano', 'asc')
            ->orderBy('mes', 'asc')
            ->get();

        $labelsMonths = [];
        $dataAppointments = [];

        foreach($montlyAppointments as $appointment) {
            $labelsMonths[] = Carbon::createFromDate($appointment->ano, $appointment->mes, 1)->translatedFormat('M/Y');
            $dataAppointments[] = $appointment->total;
        }

        // Gráfico 2: Distribuição de Animais por Espécie
        $animalsBySpecie = Specie::withCount('animals')
            ->orderBy('animals_count', 'desc')
            ->get();

        $labelsSpecies = $animalsBySpecie->pluck('name')->toArray();
        $speciesData = $animalsBySpecie->pluck('animals_count')->toArray();


        return response()->json([
            'totalTutors'       => $totalTutors,
            'totalAnimals'      => $totalAnimals,
            'todayAppointments' => $todayAppointments,
            'monthRevenue'      => $monthRevenue,
            'dataAppointments'  => $dataAppointments,
            'consultationOfDay' => $consultationOfDay,
            'labelsMonths'      => $labelsMonths,
            'labelsSpecies'     => $labelsSpecies,
            'speciesData'       => $speciesData,
        ]);
    }
}

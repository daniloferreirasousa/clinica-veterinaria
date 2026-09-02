<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function show(Request $request, Animal $animal)
    {
        $animal->load([
            'tutor',
            'specie',
            'race',
            'consultations.veterinarian',
            'exams',
            'vaccinations.veterinarian',
            'prescriptions.items',
            'prescriptions.veterinarian'
        ]);

        $veterinarians = User::where('role', 'veterinario')->where('status', true)->get();

        // Captura os filtros vindos da URL
        $startDate = $request->input('start_date');
        $finishDate = $request->input('finish_date');
        $registerType = $request->input('register_type');

        $timeline = collect();

        // Processamento de Normalização de Consultas
        if (!$registerType || $registerType === 'consulta') {
            foreach ($animal->consultations as $consultation) {
                $eventDate = Carbon::parse($consultation->date_time)->toDateString();

                if ($this->filterByDate($eventDate, $startDate, $finishDate)) {
                    $timeline->push([
                        'type' => 'consulta',
                        'title' => 'Consulta Médica Realizada',
                        'date_time' => Carbon::parse($consultation->date_time),
                        'veterinarian'  => $consultation->veterinarian->name ?? 'N/A',
                        'data'  => $consultation,
                    ]);
                }
            }
        }

        // Processamento de Normalização de Exames
        if (!$registerType || $registerType === 'exams') {
            foreach ($animal->exams as $exam) {
                $eventDate = Carbon::parse($exam->exam_date)->toDateString();

                if ($this->filterByDate($eventDate, $startDate, $finishDate)) {
                    $timeline->push([
                        'type' => 'exame',
                        'title' => 'Exame Clínico Anexado: ' . $exam->name,
                        'date_time' => Carbon::parse($exam->exam_date . ' 00:00:00' ),
                        'veterinarian' => 'Laboratório Interno',
                        'data' => $exam,
                    ]);
                }
            }
        }

        // Processamento e Normalização de Vacinas
        if (!$registerType || $registerType === 'vacina') {
            foreach ($animal->vaccinations as $vax) {
                $eventDate = Carbon::parse($vax->application_date)->toDateString();

                if ($this->filterByDate($eventDate, $startDate, $finishDate)) {
                    $timeline->push([
                        'type' => 'vacina',
                        'title' => 'Vacina Aplicada: ' . $vax->name,
                        'date_time' => Carbon::parse($vax->application_date . ' 00:00:00'),
                        'veterinarian' => $vax->veterinarian->name ?? 'N/A',
                        'data' => $vax,
                    ]);
                }
            }
        }

        // Processamento e Normalização de Receitas
        if (!$registerType || $registerType === 'receita') {
            foreach ($animal->prescriptions as $prescription) {
                $eventDate = Carbon::parse($prescription->date)->toDateString();

                if ($this->filterByDate($eventDate, $startDate, $finishDate)) {
                    $timeline->push([
                        'type' => 'receita',
                        'title' => 'Emissão de Receituário Médico',
                        'date_time' => Carbon::parse($prescription->date . ' 00:00:00'),
                        'veterinarian' => $prescription->veterinarian->name ?? 'N/A',
                        'data' => $prescription,
                    ]);
                }
            }
        }

        $timelineSorted = $timeline->sortByDesc('date_time');

        return view('historys.show', compact('animal', 'timelineSorted', 'veterinarians'));
    }


    private function filterByDate($eventDate, $startDate, $finishDate): bool
    {
        if ($startDate && $eventDate < $startDate) return false;

        if ($finishDate && $eventDate > $finishDate) return false;

        return true;
    }
}

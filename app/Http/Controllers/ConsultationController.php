<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;
use App\Models\User;
use App\Models\Tutor;
use App\Http\Requests\ConsultationRequest;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $consultations = Consultation::with(['animal.tutor', 'animal.specie', 'veterinarian'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('animal', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('tutor', fn($t) => $t->where('name', 'like', "%{$search}%"));
                })->orWhereHas('veterinarian', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('date_time', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('consultations.index', compact('consultations', 'search', 'status'));
    }

    public function create()
    {
        $tutors = Tutor::orderBy('name', 'asc')->get();
        $veterinarians = User::where('role', 'veterinario')
            ->orderBy('name', 'asc')
            ->get();

        return view('consultations.create', compact('tutors', 'veterinarians'));
    }

    public function store(ConsultationRequest $request)
    {
        Consultation::create($request->validated());

        return redirect()
            ->route('consultations.index')
            ->with('success', 'Consulta agendada com sucesso!');
    }

    public function show(Consultation $consultation)
    {
        $consultation->load([
            'animal.tutor',
            'animal.specie',
            'animal.race',
            'veterinarian',
            'prescriptions.items'
        ]);

        return view('consultations.show', compact('consultation'));
    }

    public function edit(Consultation $consultation)
    {
        $consultation->load(['animal.tutor']);
        $tutors = Tutor::orderBy('name', 'asc')->get();
        $veterinarians = User::where('role', 'veterinario')
            ->orderBy('name', 'asc')
            ->get();

        return view('consultations.edit', compact('consultation', 'tutors', 'veterinarians'));
    }

    public function update(ConsultationRequest $request, Consultation $consultation)
    {
        $consultation->update($request->validated());

        return redirect()
            ->route('consultations.index')
            ->with('success', 'Dados da consulta atualizados com sucesso!');
    }

    public function destroy(Consultation $consultation)
    {
        $consultation->delete();

        return redirect()
            ->route('consultations.index')
            ->with('success', 'Agendamento de consulta removido do sistema.');
    }
}

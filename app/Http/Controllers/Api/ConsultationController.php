<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConsultationRequest;
use App\Models\Consultation;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        return response()->json([
            $search,
            $status,
            $consultations
        ]);
    }

    public function optionsCreate()
    {
        $tutors = Tutor::orderBy('name', 'asc')->get();
        $veterinarians = User::where('role', 'veterinario')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            $tutors,
            $veterinarians,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConsultationRequest $request)
    {
        Consultation::create($request->validated());

        return response()->json([
            'success' => 'Agendamento realiazado com sucesso!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Consultation $consultation)
    {
        $consultation->load([
            'animal.tutor',
            'animal.specie',
            'animal.race',
            'veterinarian',
            'prescriptions.items'
        ]);

        return response()->json([
            $consultation
        ]);
    }

    public function optionsEdit(Consultation $consultation)
    {
        $consultation->load(['animal.tutor']);
        $tutors = Tutor::orderBy('name', 'asc')->get();
        $veterinarians = User::where('role', 'veterinario')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            $consultation,
            $tutors,
            $veterinarians
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConsultationRequest $request, Consultation $consultation)
    {
        $consultation->update($request->validated());

        return response()->json([
            $consultation,
            'message' => 'Agendamento atualizado com sucesso!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consultation $consultation)
    {
        $consultation->delete();

        return response()->json([
            'message' => 'Agendamento removido com sucesso!',
        ]);
    }
}

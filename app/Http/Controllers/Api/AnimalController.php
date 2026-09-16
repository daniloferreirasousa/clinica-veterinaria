<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnimalRequest;
use App\Models\Animal;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $animals = Animal::with(['tutor', 'specie', 'race'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('specie', fn($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('race', fn($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('tutor', fn($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            $animals,
            $search
        ]);
    }

        /**
     * Store a newly created resource in storage.
     */
    public function store(AnimalRequest $request)
    {
        $animal = Animal::create($request->validated());

        return response()->json([
            $animal
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Animal $animal)
    {
        $animal->load(['tutor', 'specie', 'race']);

        return response()->json([
            $animal
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AnimalRequest $request, Animal $animal)
    {
        $animal->update($request->validated());

        return response()->json([
            $animal,
            'success' => "Dados do animal atualizados com sucesso!"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Animal $animal)
    {
        $animal->delete();

        return response()->json([
            'success' => 'Animal removido com sucesso!'
        ]);
    }
}

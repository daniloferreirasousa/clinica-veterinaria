<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecieRequest;
use App\Models\Specie;
use Illuminate\Http\Request;

class SpecieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $species = Specie::withCount(['races', 'animals'])
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'search' => $search,
            'species' => $species
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SpecieRequest $request)
    {
        Specie::create($request->validated());

        return response()->json([
            'success' => 'Espécie criada com sucesso!'
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(SpecieRequest $request, Specie $specie)
    {
        $specie->update($request->validated());

        return response()->json([
            $specie,
            'success' => 'Specie atualizada com sucesso!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specie $specie)
    {
         if ($specie->animals()->exists()) {
            return back()
                ->with('error', 'Não é possível exluir uma espécie que possúi animais associados.');
        }

        $specie->delete();

        return response()->json([
            'success' => 'Espécie removida copm sucesso!',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RaceRequest;
use App\Models\Race;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $races = Race::with('specie')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'races' => $races,
            'search' => $search
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(RaceRequest $request)
    {
        $race = Race::create($request->validated());

        return response()->json([
            'race' => $race,
            'success' => 'Raça criada com sucesso!'
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(RaceRequest $request, Race $race)
    {
        $race->update($request->validated());

        return response()->json([
            'race' => $race,
            'success' => 'Raça atualizada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Race $race)
    {
        $race->delete();

        return response()->json([
            'success' => 'Raça removida com sucesso!'
        ]);
    }
}

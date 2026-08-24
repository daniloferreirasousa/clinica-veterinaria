<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Race;
use App\Models\Animal;

class DropdownController extends Controller
{
    public function animalsByTutor(int $tutorId): JsonResponse
    {
        $animals = Animal::where('tutor_id', $tutorId)
            ->with(['specie'])
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($animals);
    }

    public function racesBySepecie(int $specieId): JsonResponse
    {
        $races = Race::where('specie_id', $specieId)
            ->orderBy('name', 'asc')
            ->get();

        
        return response()->json($races);
    }
}

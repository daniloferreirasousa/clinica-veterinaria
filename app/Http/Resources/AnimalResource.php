<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnimalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'tutor_id'  => $this->tutor_id,
            'name'      => $this->name,
            'specie'    => $this->specie->name ?? null,
            'race'      => $this->race->name ?? null,
            'gender'    => $this->gender,
            'birth_date'    => $this->birth_date,
            'weight'    => $this->weight,
            'observations' => $this->observations,
        ];
    }
}

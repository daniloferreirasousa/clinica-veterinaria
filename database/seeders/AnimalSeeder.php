<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Race;
use App\Models\Specie;
use App\Models\Tutor;
use Illuminate\Database\Seeder;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tutorsIds = Tutor::pluck('id');
        $speciesIds = Specie::pluck('id');
        $racesIds   = Race::pluck('id');

        if ($tutorsIds->isEmpty() || $speciesIds->isEmpty() || $racesIds->isEmpty()) {
            $this->command->warn('Nenhum tutor encontrado no banco. Cadastre tutores antes de rodar este Seeder.');
            return;
        }

        Animal::factory(25)->create([
            'tutor_id'  => fn () => $tutorsIds->random(),
            'specie_id' => fn () => $speciesIds->random(),
            'race_id'   => fn () => $racesIds->random(),
        ]);
    }
}

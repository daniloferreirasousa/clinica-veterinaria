<?php

namespace Database\Seeders;

use Database\Seeders\AnimalSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserSeeder::class,
            SpecieRaceSeeder::class,
            TutorSeeder::class,
            AnimalSeeder::class,
        ]);
    }
}

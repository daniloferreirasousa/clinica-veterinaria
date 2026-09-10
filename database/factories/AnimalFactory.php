<?php

namespace Database\Factories;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Animal>
 */
class AnimalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'          => fake('pt_BR')->firstName(),
            'gender'        => fake()->randomElement(['male', 'female']),
            'birth_date'    => fake()->date('Y-m-d', '-1 year'),
            'weight'        => fake()->randomFloat(2, 0.5, 40),
            'observation'   => fake('pt_BR')->optional()->sentence(),
        ];
    }
}

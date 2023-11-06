<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'person_id' => $this->faker->randomElement(\App\Models\Person::all()->pluck('id')->toArray()),
            'description' => $this->faker->text(),
            'speciality' => $this->faker->randomElement(['Cardiologist', 'Neurologist', 'Pediatrician']),
            'degree_place' => $this->faker->randomElement(['Universidad de Chile', 'Universidad de Perú', 'Universidad de Colombia']),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalCenter>
 */
class MedicalCenterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence(6),
            'address' => $this->faker->address,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'opening_datetime' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'closing_datetime' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'phones' => $this->faker->phoneNumber,
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EventType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_type_id' => EventType::all()->random()->id,
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(6),
            'end_date' => $this->faker->date(),
            'step' => json_encode([
                'hola' => 'hola123'
            ]),
            'area' => json_encode([
                'hola' => 'hola123'
            ]),
            'requirement' => json_encode([
                'hola' => 'hola123'
            ]),
        ];
    }
}

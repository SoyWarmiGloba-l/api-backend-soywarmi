<?php

namespace Database\Factories;

use App\Models\EventType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
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
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->text(200),
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(7),
            'areas' => json_encode([
                'News',
                'Testimonios',
                'Noticias',
                'Eventos y Conferencias',
            ]),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role_id' => $this->faker->randomElement(\App\Models\Role::all()->pluck('id')->toArray()),
            'team_id' => $this->faker->randomElement(\App\Models\Team::all()->pluck('id')->toArray()),
            'name' => $this->faker->name(),
            'lastname' => $this->faker->lastName(),
            'mother_lastname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make(123456),
            'photo' => 'https://images.pexels.com/photos/220453/pexels-photo-220453.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            'birthday' => $this->faker->date(),
            'phone' => 123456,
            'gender' => $this->faker->randomElement(['Masculino', 'Femenino', 'No especificado']),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'email' => fake()->unique()->safeEmail(),
            'first_name' => fake()->name(),
            'last_name' => fake()->lastName(),
            'phone_number' => fake()->numerify('########'),
            'home_address' => fake()->address(),
            'dni' => fake()->numerify('###-######-####').mb_strtoupper(fake()->randomLetter()),
        ];
    }
}

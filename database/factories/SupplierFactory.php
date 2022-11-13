<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'RUC' => fake()->uuid(),
            'address' => fake()->address(),
            'agent_name' => fake()->company(),
            'branch' => fake()->company(),
            'email' => fake()->email(),
            'phone_number' => fake()->phoneNumber(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seller>
 */
class SellerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->name(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'password' => bcrypt('password'),
            'carnet' => now()->year.'-'.Str::random(5),
            'hired_at' => now(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Seller $seller) {
            $seller->assign('seller');
        });
    }
}

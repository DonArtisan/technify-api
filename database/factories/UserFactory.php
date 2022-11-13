<?php

namespace Database\Factories;

use App\Http\Stats\UserStats;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        UserStats::increase(1);

        return [
            'email' => fake()->safeEmail(),
            'email_verified_at' => now(),
            'first_name' => fake()->name(),
            'is_admin' => fake()->randomElement([true, false]),
            'last_name' => fake()->lastName(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            if ($user->is_admin) {
                $user->assign('admin');
            }
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}

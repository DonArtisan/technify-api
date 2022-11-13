<?php

namespace Database\Factories;

use App\Models\ProductSale;
use App\Models\ProductSaleDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSale>
 */
class ProductSaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $buyerable = $this->buyerable();

        return [
            'buyerable_id' => $buyerable::factory(),
            'buyerable_type' => $buyerable,
            'amount' => 0,
            'tax' => 0.15,
            'total' => 0,
        ];
    }

    public function buyerable()
    {
        return $this->faker->randomElement([
            User::class,
        ]);
    }

    public function configure()
    {
        logger('what');

        return $this->afterCreating(function (ProductSale $sale) {
            $r = rand(1, 3);
            for ($i = 0; $i < $r; $i++) {
                ProductSaleDetail::factory()->create([
                    'sale_id' => $sale->id,
                ]);
            }
        });
    }
}
<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductSale;
use App\Models\ProductSaleDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSaleDetail>
 */
class ProductSaleDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $product = Product::query()->inRandomOrder()->first();
        $price = $product->prices()->first()->price;
        return [
            'sale_id' => ProductSale::factory(),
            'product_id' => $product->id,
            'quantity' => $this->faker->numberBetween(1, 10),
            'price' => $price,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (ProductSaleDetail $saleDetail) {
            $sale = $saleDetail->sale()->get()->first();
            $amount = $sale->amount + ($saleDetail->price * $saleDetail->quantity);
            $total = ($sale->tax * $amount) + $amount;
            $sale->update(['amount' => $amount, 'total' => $total]);
        });
    }
}

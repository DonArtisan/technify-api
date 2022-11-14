<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Color;
use App\Models\Model;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->sentence(2),
            'description' => fake()->paragraph(2),
            'category_id' => Category::factory(),
            'color_id' => Color::factory(),
            'model_id' => Model::factory(),
            'status' => ProductStatus::getRandomValue(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            Price::factory()->create([
                'product_id' => $product->id,
            ]);
        });
    }
}

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
            'sale_price' => fake()->numerify(),
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Product $product) {
            $product->stock()->create(['quantity' => fake()->randomNumber()]);
            $url = 'https://source.unsplash.com/random/400x400';
            $product->addMediaFromUrl($url)->toMediaCollection(Product::MEDIA_COLLECTION_IMAGE);

            Price::factory()->create([
                'product_id' => $product->id,
            ]);
        });
    }

//    public function configure()
//    {
//        return $this->afterCreating(function (Post $post) {
//            $url = 'https://source.unsplash.com/random/1200x800';
//            $post
//                ->addMediaFromUrl($url)
//                ->toMediaCollection(Post::MEDIA_COLLECTION_BANNER);
//        });
//    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::pluck('id');
//        $categories = \App\ProductCategory::pluck('id');
        $categories->each(function ($category) {
            Product::factory()->count(10)->create([
                'category_id' => $category,
            ]);
        });
        //
    }
}

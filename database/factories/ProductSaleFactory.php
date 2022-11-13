<?php

namespace Database\Factories;

use App\Models\ProductSale;
use App\Models\ProductSaleDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'buyerable'=> User::factory(),
            'amount' => '0',
            'tax' => '0.5',
            'total'=> '0'
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (ProductSale $sale )
        {
            ProductSaleDetail::factory()->create([
                'sale_id' => $sale->id,
            ]);
        });
    }


//$table->morphs('buyerable');
//$table->decimal('amount');
//$table->decimal('tax');
//$table->decimal('total');
//$table->timestamps();
}

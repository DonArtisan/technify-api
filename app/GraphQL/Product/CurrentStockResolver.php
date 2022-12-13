<?php

namespace App\GraphQL\Product;

use App\Models\Product;

class CurrentStockResolver
{
    public function __invoke(Product $product): ?int
    {
        return $product->stock->quantity;
    }
}

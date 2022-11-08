<?php

namespace App\GraphQL\Product;

use App\Models\Product;

class CurrentPriceResolver
{
    public function __invoke(Product $product): ?float
    {
        return $product->prices()->latest()->first()?->price;
    }
}

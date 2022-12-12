<?php

namespace App\GraphQL\Product\Builders;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductPaginateBuilder extends Builder
{
    public function __invoke($root): Builder
    {
        return Product::query()->whereHas('stock', function ($query) {
            $query->where('quantity', '>', 0);
        });
    }
}

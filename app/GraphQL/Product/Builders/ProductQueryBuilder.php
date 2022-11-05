<?php

namespace App\GraphQL\Product\Builders;

use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilder extends Builder
{
    public function __invoke(Builder $builder, ?string $query): Builder
    {
        if ($query) {
            $builder->where('name', 'ILIKE', "%$query%");
        }

        return $builder;
    }
}

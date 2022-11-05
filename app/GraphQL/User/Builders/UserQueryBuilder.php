<?php

namespace App\GraphQL\User\Builders;

use Illuminate\Database\Eloquent\Builder;

class UserQueryBuilder extends Builder
{
    public function __invoke(Builder $builder, ?string $query): Builder
    {
        if ($query) {
            $builder->where("CONCAT_WS(' ', first_name, last_name) ILIKE ?", "%$query%");
        }

        return $builder;
    }
}

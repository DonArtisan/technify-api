<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Schema\TypeRegistry;
use Nuwave\Lighthouse\Schema\Types\LaravelEnumType;

class GraphQLServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot(TypeRegistry $typeRegistry): void
    {
        $enums = [
            \App\Enums\ProductStatus::class,
        ];

        foreach ($enums as $enum) {
            $typeRegistry->register(
                new LaravelEnumType($enum)
            );
        }
    }
}

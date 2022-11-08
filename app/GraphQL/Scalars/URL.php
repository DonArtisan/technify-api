<?php

namespace App\GraphQL\Scalars;

use League\Uri\Contracts\UriException;
use League\Uri\Uri;
use MLL\GraphQLScalars\StringScalar;

class URL extends StringScalar
{
    protected function isValid(string $stringValue): bool
    {
        try {
            Uri::createFromString($stringValue);

            return true;
        } catch (UriException $e) {
            return false;
        }
    }
}

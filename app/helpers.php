<?php

use Illuminate\Support\Str;

if (! function_exists('getRandomCarnet')) {
    function getRandomCarnet(): string
    {
        return now()->year.'-'.Str::random(5);
    }
}

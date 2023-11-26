<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Pricelist;
use App\PricelistRange;
use Faker\Generator as Faker;

$factory->define(Pricelist::class, function (Faker $faker) {
    $extra_day_cost = (float)rand(10, 30);

    return [
        'extra_day_cost' => $extra_day_cost,
    ];
});

$factory->define(PricelistRange::class, function (Faker $faker) {
    $minimum_days = rand(1, 5);
    $maximum_days = $minimum_days + rand(0, 5);
    $cost         = (float)rand(10, 30);

    return [
        'minimum_days' => $minimum_days,
        'maximum_days' => $maximum_days,
        'cost'         => $cost,
    ];
});

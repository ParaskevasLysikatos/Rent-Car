<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FuelTypes;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(FuelTypes::class, function (Faker $faker) {
    $title = Arr::random(['Electric', 'Gas/LPG', 'Gasoline', 'Hybrid Gas', 'Hybrid Petrol', 'Natural Gas', 'Petroleum']);
    return [
        'title' => $title,
        'international_title' => $title
    ];
});

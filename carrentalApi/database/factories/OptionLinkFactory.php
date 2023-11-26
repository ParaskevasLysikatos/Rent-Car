<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\OptionLink;
use Faker\Generator as Faker;

$factory->define(OptionLink::class, function (Faker $faker) {
    return [
        'ordering' => $faker->numberBetween(1, 100)
    ];
});

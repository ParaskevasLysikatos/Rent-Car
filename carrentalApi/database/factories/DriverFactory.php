<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Driver;
use Faker\Generator as Faker;

$factory->define(Driver::class, function (Faker $faker) {
    return [
        'notes'                  => $faker->text,
        'licence_number'         => $faker->numberBetween(1000, 10000),
        'licence_country'        => $faker->country,
        'licence_created'        => $faker->date(),
        'licence_expire'         => $faker->date()
    ];
});

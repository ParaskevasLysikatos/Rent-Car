<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use App\Driver;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name'              =>  'Company ' . $faker->randomNumber(4) . $faker->randomNumber(4),
        'afm'               =>  $faker->randomNumber(5),
        'doy'               =>  $faker->randomNumber(5),
        'country'           =>  $faker->country,
        'city'              =>  $faker->city,
        'job'               =>  $faker->jobTitle,
        'phone'             =>  $faker->phoneNumber,
        'email'             =>  $faker->email,
        'website'           =>  $faker->domainName,
    ];
});

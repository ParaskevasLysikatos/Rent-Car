<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ContactInformation;
use Faker\Generator as Faker;

$factory->define(ContactInformation::class, function (Faker $faker) {
    return [
        'fullname' => $faker->name,
        'email'    => $faker->email,
        'mobile'   => $faker->phoneNumber,
        'phone'    => $faker->phoneNumber,
        'fax'      => $faker->phoneNumber,
        'address'  => $faker->address,
        'postcode' => $faker->postcode,
        'city'     => $faker->city,
        'country'  => $faker->country,
    ];
});

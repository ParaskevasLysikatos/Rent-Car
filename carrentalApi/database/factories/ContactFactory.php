<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Contact;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
    return [
        'firstname' => $faker->firstName,
        'lastname'  => $faker->lastName,
        'email'     => $faker->email,
        'phone'     => $faker->phoneNumber,
        'identification_number'  => $faker->numberBetween(1000, 10000),
        'identification_country' => $faker->country,
        'identification_created' => $faker->date(),
        'identification_expire'  => $faker->date()
    ];
});

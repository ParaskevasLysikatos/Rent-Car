<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Characteristic;
use App\CharacteristicProfile;
use Faker\Generator as Faker;

$factory->define(Characteristic::class, function (Faker $faker) {
    $slug = 'characteristic-' . $faker->randomNumber(4) . $faker->randomNumber(4);

    return [
        'slug' => $slug,
    ];
});

$factory->afterCreatingState(Characteristic::class, 'with_profiles', function ($characteristic, $faker) {
    factory(CharacteristicProfile::class)->create([
        'characteristic_id' => $characteristic->id,
        'language_id'       => 'el',
        'title'             => 'Χαρακτηριστικό ' . $characteristic->id,
    ]);

    factory(CharacteristicProfile::class)->create([
        'characteristic_id' => $characteristic->id,
        'language_id'       => 'en',
        'title'             => 'Characteristic ' . $characteristic->id,
    ]);
});

$factory->define(CharacteristicProfile::class, function (Faker $faker) {
    $language_id = Arr::random(['el', 'en']);

    return [
        'language_id' => $language_id,
        'title'       => $faker->text(50),
        'description' => $faker->text(200),
    ];
});

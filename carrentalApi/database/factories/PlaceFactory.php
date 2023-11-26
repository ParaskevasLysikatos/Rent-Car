<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Place;
use App\PlaceProfile;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Place::class, function (Faker $faker) {
    $slug = 'place-' . $faker->randomNumber(4) . $faker->randomNumber(4);

    return [
        'slug'      => $slug,
        'latitude'  => $faker->latitude,
        'longitude' => $faker->longitude,
    ];
});

$factory->afterCreatingState(Place::class, 'with_profiles', function ($place, $faker) {
    factory(PlaceProfile::class)->create([
        'place_id'  => $place->id,
        'language_id' => 'el',
        'title'       => 'Τοποθεσία ' . $place->id,
    ]);

    factory(PlaceProfile::class)->create([
        'place_id'  => $place->id,
        'language_id' => 'en',
        'title'       => 'Τοποθεσία ' . $place->id,
    ]);
});

$factory->define(PlaceProfile::class, function (Faker $faker) {
    $language_id = Arr::random(['el', 'en']);

    return [
        'language_id' => $language_id,
        'title'       => $faker->text(50),
        'description' => $faker->text(200),
    ];
});

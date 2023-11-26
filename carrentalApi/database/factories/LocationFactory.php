<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Location;
use App\LocationProfile;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Location::class, function (Faker $faker) {
    $slug = 'location-' . $faker->randomNumber(4) . $faker->randomNumber(4);

    return [
        'slug'      => $slug,
        'latitude'  => $faker->latitude,
        'longitude' => $faker->longitude,
    ];
});

$factory->define(LocationProfile::class, function (Faker $faker) {
    $language_id = Arr::random(['el', 'en']);

    return [
        'language_id' => $language_id,
        'title'       => $faker->text(50),
    ];
});

$factory->afterCreatingState(Location::class, 'with_profiles', function ($location, $faker) {
    factory(LocationProfile::class)->create([
        'location_id' => $location->id,
        'language_id' => 'el',
        'title'       => 'Γεωγραφικό Διαμέρισμα ' . $location->id,
    ]);

    factory(LocationProfile::class)->create([
        'location_id' => $location->id,
        'language_id' => 'en',
        'title'       => 'Location ' . $location->id,
    ]);
});

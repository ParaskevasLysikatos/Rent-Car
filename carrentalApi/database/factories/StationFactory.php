<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Location;
use App\Station;
use App\StationProfile;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Station::class, function (Faker $faker) {
    $slug = 'station-' . $faker->randomNumber(4) . $faker->randomNumber(4);

    return [
        'slug'      => $slug,
        'latitude'  => $faker->latitude,
        'longitude' => $faker->longitude,
    ];
});

$factory->afterMakingState(Station::class, 'with_location', function ($station, $faker) {
    $station->location_id = factory(Location::class)->create()->id;
});

$factory->afterCreatingState(Station::class, 'with_profiles', function ($station, $faker) {
    factory(StationProfile::class)->create([
        'station_id'  => $station->id,
        'language_id' => 'el',
        'title'       => 'Σταθμός ' . $station->id,
    ]);

    factory(StationProfile::class)->create([
        'station_id'  => $station->id,
        'language_id' => 'en',
        'title'       => 'Station ' . $station->id,
    ]);
});

$factory->define(StationProfile::class, function (Faker $faker) {
    $language_id = Arr::random(['el', 'en']);

    return [
        'language_id' => $language_id,
        'title'       => $faker->text(50),
        'description' => $faker->text(200),
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Season;
use App\SeasonProfile;
use Faker\Generator as Faker;

$factory->define(Season::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->define(SeasonProfile::class, function (Faker $faker) {
    $language_id = Arr::random(['el', 'en']);

    return [
        'language_id' => $language_id,
        'title'       => $faker->text(50),
        'description' => $faker->text(200),
    ];
});

$factory->afterCreatingState(Season::class, 'with_profiles', function ($season, $faker) {


    factory(SeasonProfile::class)->create([
        'season_id'   => $season->id,
        'language_id' => 'el',
        'title'       => 'Σεζόν ' . $season->id,
    ]);

    factory(SeasonProfile::class)->create([
        'season_id'   => $season->id,
        'language_id' => 'en',
        'title'       => 'Season ' . $season->id,
    ]);
});

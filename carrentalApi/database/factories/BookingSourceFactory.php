<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BookingSource;
use App\BookingSourceProfile;
use Faker\Generator as Faker;


$factory->define(BookingSource::class, function (Faker $faker) {
    $slug = 'bookingsource-' . $faker->randomNumber(4) . $faker->randomNumber(4);

    return [
        'slug' => $slug,
    ];
});

$factory->afterCreatingState(BookingSource::class, 'with_profiles', function ($booking_source, $faker) {
    factory(BookingSourceProfile::class)->create([
        'booking_source_id' => $booking_source->id,
        'language_id' => 'el',
        'title'       => 'Πηγή ' . $booking_source->id,
    ]);

    factory(BookingSourceProfile::class)->create([
        'booking_source_id' => $booking_source->id,
        'language_id' => 'en',
        'title'       => 'Source ' . $booking_source->id,
    ]);
});

$factory->define(BookingSourceProfile::class, function (Faker $faker) {
    $language_id = Arr::random(['el', 'en']);

    return [
        'language_id' => $language_id,
        'title'       => $faker->text(50),
        'description' => $faker->text(200),
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Option;
use App\OptionProfile;
use Faker\Generator as Faker;

$factory->define(Option::class, function (Faker $faker) {
    $slug = 'option-' . $faker->randomNumber(4) . $faker->randomNumber(4);

    return [
        'slug'       => $slug,
        'cost'       => $faker->randomFloat(2, 0, 100),
        'cost_daily' => $faker->randomFloat(2, 0, 25),
        'cost_max'   => $faker->randomFloat(2, 0, 100),
        'items_max'  => $faker->randomNumber(),
    ];
});

$factory->afterCreatingState(Option::class, 'with_profiles', function ($option, $faker) {
    factory(OptionProfile::class)->create([
        'option_id'   => $option->id,
        'language_id' => 'el',
        'title'       => 'Παροχή ' . $option->id,
    ]);

    factory(OptionProfile::class)->create([
        'option_id'   => $option->id,
        'language_id' => 'en',
        'title'       => 'Option ' . $option->id,
    ]);
});

$factory->define(OptionProfile::class, function (Faker $faker) {
    $language_id = Arr::random(['el', 'en']);

    return [
        'language_id' => $language_id,
        'title'       => $faker->text(50),
        'description' => $faker->text(200),
    ];
});

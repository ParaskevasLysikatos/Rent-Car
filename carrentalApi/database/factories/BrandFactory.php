<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Brand;
use App\BrandProfile;
use Faker\Generator as Faker;

$factory->define(Brand::class, function (Faker $faker) {
    $slug = 'brand-' . $faker->randomNumber(4) . $faker->randomNumber(4);

    return [
        'slug' => $slug,
    ];
});

$factory->afterCreatingState(Brand::class, 'with_profiles', function ($brand, $faker) {
    factory(BrandProfile::class)->create([
        'brand_id'    => $brand->id,
        'language_id' => 'el',
        'title'       => 'Brand ' . $brand->id,
    ]);

    factory(BrandProfile::class)->create([
        'brand_id'    => $brand->id,
        'language_id' => 'en',
        'title'       => 'Brand ' . $brand->id,
    ]);
});

$factory->define(BrandProfile::class, function (Faker $faker) {
    $language_id = Arr::random(['el', 'en']);

    return [
        'language_id' => $language_id,
        'title'       => $faker->text(50),
        'description' => $faker->text(200),
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\CategoryProfile;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Category::class, function (Faker $faker) {
    $slug = 'category-' . $faker->randomNumber(4) . $faker->randomNumber(4);

    return [
        'slug' => $slug,
    ];
});

$factory->afterCreatingState(Category::class, 'with_profiles', function ($category, $faker) {
    factory(CategoryProfile::class)->create([
        'category_id' => $category->id,
        'language_id' => 'el',
        'title'       => 'Κατηγορία ' . $category->id,
    ]);

    factory(CategoryProfile::class)->create([
        'category_id' => $category->id,
        'language_id' => 'en',
        'title'       => 'Category ' . $category->id,
    ]);
});

$factory->define(CategoryProfile::class, function (Faker $faker) {
    $language_id = Arr::random(['el', 'en']);

    return [
        'language_id' => $language_id,
        'title'       => $faker->text(50),
        'description' => $faker->text(200),
    ];
});

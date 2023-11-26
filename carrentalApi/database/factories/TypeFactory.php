<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Characteristic;
use App\Image;
use App\Option;
use App\OptionLink;
use App\Type;
use App\TypeCharacteristic;
use App\TypeImage;
use App\TypeProfile;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Type::class, function (Faker $faker) {
    $slug = 'type-' . $faker->randomNumber(4) . $faker->randomNumber(4);
    return [
        'slug'        => $slug,
        'category_id' => factory(Category::class),
    ];
});

$factory->define(TypeProfile::class, function (Faker $faker) {
    $language_id = Arr::random(['el', 'en']);

    return [
        'language_id' => $language_id,
        'title'       => $faker->text(50),
        'description' => $faker->text(200),
    ];
});

$factory->define(TypeCharacteristic::class, function (Faker $faker) {
    return [
        'ordering' => $faker->numberBetween(1, 100),
    ];
});

$factory->define(TypeImage::class, function (Faker $faker) {
    return [
        'is_main'  => $faker->boolean,
        'ordering' => $faker->numberBetween(1, 100),
    ];
});

$factory->afterCreatingState(Type::class, 'with_profiles', function ($type, $faker) {
    factory(TypeProfile::class)->create([
        'type_id'     => $type->id,
        'language_id' => 'el',
        'title'       => 'Τύπος ' . $type->id,
    ]);

    factory(TypeProfile::class)->create([
        'type_id'     => $type->id,
        'language_id' => 'en',
        'title'       => 'Type ' . $type->id,
    ]);
});

$factory->afterCreatingState(Type::class, 'with_characteristics', function ($type, $faker) {
    $characteristics = Characteristic::inRandomOrder()->limit(10)->get();

    foreach ($characteristics as $item) {
        factory(TypeCharacteristic::class)->create([
            'type_id'           => $type->id,
            'characteristic_id' => $item->id,
        ]);
    }
});

$factory->afterCreatingState(Type::class, 'with_options', function ($type, $faker) {
    $options = Option::inRandomOrder()->limit(5)->get();

    foreach ($options as $item) {
        factory(OptionLink::class)->create([
            'option_link_id'   => $type->id,
            'option_link_type' => Type::class,
            'option_id' => $item->id,
        ]);
    }
});

$factory->afterCreatingState(Type::class, 'with_images', function ($type, $faker) {
    $images = Image::inRandomOrder()->limit(5)->get();

    foreach ($images as $item) {
        factory(TypeImage::class)->create([
            'type_id'  => $type->id,
            'image_id' => $item->id,
        ]);
    }
});

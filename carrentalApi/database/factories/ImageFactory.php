<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {

    $ext  = rand(0, 1) == 0 ? 'png' : 'jpg';
    $path = 'images/image-' . $faker->randomNumber(4) . $faker->randomNumber(4) . '.' . $ext;

    return [
        'path'      => $path,
        'mime_type' => $ext == 'png' ? 'image/png' : 'image/jpeg',
    ];
});

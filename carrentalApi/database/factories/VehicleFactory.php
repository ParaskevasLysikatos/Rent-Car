<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use App\LicencePlate;
use App\Type;
use App\Vehicle;
use App\VehicleImage;
use App\VehicleProfile;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Vehicle::class, function (Faker $faker) {
    [$make, $model] = Arr::random([
        ['SEAT', 'Ibiza'],
        ['SEAT', 'Leon'],
        ['SEAT', 'Arona'],
        ['Renault', 'Captur'],
        ['Renault', 'Kadjar'],
        ['Suzuki', 'Vitara'],
        ['Suzuki', 'Swift'],
    ]);

    return [
        'vin'          => $faker->randomNumber(6) . $faker->randomNumber(6) . $faker->randomNumber(6),
        'make'         => $make,
        'model'        => $model,
        'engine'       => $faker->numberBetween(600, 2000) . ' cc',
        'power'        => $faker->numberBetween(35, 500) . ' bhp',
        'drive_type'   => Arr::random(['FWD', 'AWD', 'RWD']),
        'transmission' => Arr::random(['manual', 'auto', 'semiauto']),
        'doors'        => $faker->numberBetween(2, 7),
        'seats'        => $faker->numberBetween(2, 12),
        'euroclass'    => Arr::random(['Euro 1', 'Euro 2', 'Euro 3', 'Euro 4', 'Euro 5', 'Euro 6', 'Euro 6c', 'Euro 6d']),
    ];
});

$factory->afterMakingState(Vehicle::class, 'with_type', function ($vehicle, $faker) {
    $type             = factory(Type::class)->create();
    $vehicle->type_id = $type->id;
});


$factory->afterCreatingState(Vehicle::class, 'with_images', function ($vehicle, $faker) {
    $images = Image::inRandomOrder()->limit(5)->get();

    foreach ($images as $item) {
        factory(VehicleImage::class)->create([
            'vehicle_id' => $vehicle->id,
            'image_id'   => $item->id,
        ]);
    }
});

$factory->afterCreatingState(Vehicle::class, 'with_license_plates', function ($car, $faker) {
    $max = rand(1, 3);

    for ($i = 1; $i <= $max; $i++) {
        factory(LicencePlate::class)->create([
            'vehicle_id' => $car->id,
        ]);
    }
});

$factory->afterCreatingState(Vehicle::class, 'with_profiles', function ($car, $faker) {
    $title = $car->make . ' ' . $car->model;

    factory(VehicleProfile::class)->create([
        'vehicle_id'  => $car->id,
        'language_id' => 'el',
        'title'       => $title . ' - Ελληνικά',
    ]);

    factory(VehicleProfile::class)->create([
        'vehicle_id'  => $car->id,
        'language_id' => 'en',
        'title'       => $title . ' - English',
    ]);
});

$factory->define(VehicleImage::class, function (Faker $faker) {
    return [
        'is_main'  => $faker->boolean,
        'ordering' => $faker->numberBetween(1, 100),
    ];
});

$factory->define(VehicleProfile::class, function (Faker $faker) {
    $language_id = Arr::random(['el', 'en']);

    return [
        'language_id' => $language_id,
        'title'       => $faker->text(50),
        'description' => $faker->text(200),
    ];
});

$factory->define(LicencePlate::class, function (Faker $faker) {
    return [
        'licence_plate'     => strtoupper($faker->lexify('???')) . ' ' . $faker->numerify('####'),
        'registration_date' => $faker->dateTimeThisDecade,
    ];
});

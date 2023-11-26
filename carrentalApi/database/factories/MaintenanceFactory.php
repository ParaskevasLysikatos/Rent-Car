<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Maintenance;
use App\User;
use App\Vehicle;
use Faker\Generator as Faker;

$factory->define(Maintenance::class, function (Faker $faker) {
    return [
        'type' => 'maintenance-type-' . rand(1, 5),
    ];
});

$factory->afterMakingState(Maintenance::class, 'with_user', function ($maintenance, $faker) {
    $maintenance->user_id = factory(User::class)->state('with_role')->create();
});

$factory->afterMakingState(Maintenance::class, 'with_vehicle', function ($maintenance, $faker) {
    $maintenance->vehicle_id = factory(Vehicle::class)->state('with_type')->create();
});

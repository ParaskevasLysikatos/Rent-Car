<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Maintenance;
use App\Station;
use App\Transition;
use App\User;
use App\Vehicle;
use Faker\Generator as Faker;

$factory->define(Transition::class, function (Faker $faker) {
    return [];
});

$factory->afterMakingState(Transition::class, 'with_stations', function ($transition, $faker) {
    $transition->station_id_from = factory(Station::class)->state('with_location')->create()->id;
    $transition->station_id_to   = factory(Station::class)->state('with_location')->create()->id;
});

$factory->afterMakingState(Transition::class, 'with_user', function ($transition, $faker) {
    $transition->user_id = factory(User::class)->state('with_role')->create();
});

$factory->afterMakingState(Transition::class, 'with_vehicle', function ($transition, $faker) {
    $transition->vehicle_id = factory(Vehicle::class)->state('with_type')->create();
});

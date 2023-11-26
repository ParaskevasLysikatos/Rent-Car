<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ActionLog;
use Faker\Generator as Faker;

$factory->define(ActionLog::class, function (Faker $faker) {
    return [
        'user_id' => User::all()->random()->id
    ];
});

$factory->afterMaking(ActionLog::class, function ($action_log, Faker $faker) {
    $action_log->user_id = User::all()->random()->id;
});

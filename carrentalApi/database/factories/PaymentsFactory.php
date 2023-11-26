<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Payment;
use App\User;
use Faker\Generator as Faker;

$factory->define(Payment::class, function (Faker $faker) {
    $amount = rand(1, 5) * 10;

    return [
        'amount' => $amount,
    ];
});

$factory->afterMakingState(Payment::class, 'with_user', function ($payment, $faker) {
    $payment->user_id = factory(User::class)->state('with_role')->create()->id;
});

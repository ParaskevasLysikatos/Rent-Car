<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\UserRole;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$factory->define(UserRole::class, function (Faker $faker) {
    $digit = $faker->randomNumber(4) . $faker->randomNumber(4);

    return [
        'id'    => 'user-role-' . $digit,
        'title' => 'User role ' . $digit,
    ];
});

$factory->afterCreatingState(UserRole::class, 'with_users', function ($role, $faker) {
    factory(User::class, 10)->create([
        'role_id' => $role->id,
    ]);
});

$factory->define(User::class, function (Faker $faker) {
    return [
        'name'              => $faker->name,
        'email'             => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password'          => Hash::make('1234'),
        'remember_token'    => Str::random(10),
        'phone'             => $faker->phoneNumber,
    ];
});

$factory->afterMakingState(User::class, 'with_role', function ($user, $faker) {
    $role          = factory(UserRole::class)->create();
    $user->role_id = $role->id;
});

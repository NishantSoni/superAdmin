<?php

use Faker\Generator as Faker;

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

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'username' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('secret'),
        'address' => $faker->address,
        'house_number' => $faker->numberBetween(1, 1000),
        'postal_code' => $faker->numberBetween(1000, 999999),
        'city' => $faker->city,
        'telephone_number' => $faker->unique()->numberBetween(1000000000, 9999999999),
        'is_active' => 1,
        'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
    ];
});

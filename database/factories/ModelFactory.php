<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Jobs\Jobs::class, function (Faker\Generator $faker) {
  return [
        'name'=>$faker->jobTitle,
        'desc'=>$faker->text(200),
        'end_date'=>\Carbon\Carbon::now()->addDays(rand(1,5)),
        'price'=>$faker->randomNumber(2),
        'user_id'=>1,
        'difficulty_level_id'=>rand(1,3)
    ];
});
//TODO this code was added
$factory->define(App\Models\Jobs\Categories::class, function (Faker\Generator $faker) {
    return [
        'name'      => $faker->sentence,
        'desc'      => $faker->sentence,
        'cat_order' => $faker->randomDigitNotNull,
        'parent_id' => null
    ];
});
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
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Form::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'description' => $faker->sentence,
        'active' => true,
        'open_date' => '1990-01-01',
        'close_date' => '2990-01-01',
        'user_id' => function () {
            return factory(\App\User::class)->create()->id;
        },
    ];
});

$factory->define(App\Question::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'type' => 'text',
        'form_id' => function () {
            return factory(\App\Form::class)->create()->id;
        },
        'help_text' => $faker->sentence,
        'required' => $faker->boolean,
        'admin_only' => $faker->boolean,
        'order' => $faker->randomDigitNotNull,
    ];
});

$factory->define(App\SelectOption::class, function (Faker $faker) {
    return [
        'question_id' => function () {
            return factory(\App\Question::class)->create()->id;
        },
        'value' => $faker->word,
        'display_value' => $faker->word
    ];
});

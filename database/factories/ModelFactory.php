<?php

use App\Folder;
use App\Form;
use App\FormResponse;
use App\FormUser;
use App\Question;
use App\SelectOption;
use App\User;
use App\VisibilityRequirement;
use Faker\Factory;
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

/** @var Factory $factory */
$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'username' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Form::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'description' => $faker->sentence,
        'active' => true,
        'open_date' => '1990-01-01',
        'close_date' => '2990-01-01',
        'admin_email' => null,
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'folder_id' => function () {
            return factory(Folder::class)->create()->id;
        },
    ];
});

$factory->define(Question::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'type' => 'text',
        'form_id' => function () {
            return factory(Form::class)->create()->id;
        },
        'help_text' => $faker->sentence,
        'required' => false,
        'order' => $faker->randomDigitNotNull,
    ];
});

$factory->define(SelectOption::class, function (Faker $faker) {
    return [
        'question_id' => function () {
            return factory(Question::class)->create()->id;
        },
        'value' => $faker->word,
        'display_value' => $faker->word
    ];
});

$factory->define(VisibilityRequirement::class, function (Faker $faker) {
    return [
        'question_id' => function () {
            return factory(Question::class)->create()->id;
        },
        'required_question_id' => function () {
            return factory(Question::class)->create()->id;
        },
        'required_value' => $faker->word,
    ];
});

$factory->define(FormResponse::class, function (Faker $faker) {
    return [
        'form_id' => function () {
            return factory(Form::class)->create()->id;
        },
        'response' => $faker->word
    ];
});

$factory->define(Folder::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->define(FormUser::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'form_id' => function () {
            return factory(Form::class)->create()->id;
        },
        'access' => 'update'
    ];
});

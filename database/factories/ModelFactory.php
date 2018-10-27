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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'id' => \PascalDeVink\ShortUuid\ShortUuid::uuid4(),
        'username' => $faker->userName,
        'password' => app('hash')->make(\App\Helper::sha256($faker->password())),
        'realname' => \App\Helper::generateChinese(),
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'github' => $faker->userName,
    ];
});

$factory->define(\App\Models\Classification::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->words(3),
        'description' => $faker->paragraph(),
    ];
});

$factory->define(\App\Models\Algorithm::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->words(3),
        'pseudoCode' => [$faker->sentence(), $faker->sentence(), $faker->sentence()],
        'jsCode' => [$faker->sentence(), $faker->sentence(), $faker->sentence()],
        'CPlusCode' => [$faker->sentence(), $faker->sentence(), $faker->sentence()],
        'explain' => [$faker->sentence(), $faker->sentence(), $faker->sentence()],
        'problem' => [[
            'name' => $faker->words(5),
            'link' => 'http://www.testOJ.com/' . rand(1, 1000000),
        ]],
    ];
});
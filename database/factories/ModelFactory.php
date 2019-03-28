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
        'realName' => \App\Helper::generateChinese(),
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'github' => $faker->userName,
    ];
});

$factory->define(\App\Models\Classification::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->words(3, true),
        'description' => $faker->paragraph(),
    ];
});

$factory->define(\App\Models\Algorithm::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->words(3, true),
        'blocksXml' => '<xml xmlns="http://www.w3.org/1999/xhtml"><variables><variable type="" id="6|^2K*0cdi1H!-+VS)(*">1</variable></variables><block type="variables_get" id=";pM4/qqG77O=S#^c(,]J" x="80" y="10"><field name="VAR" id="6|^2K*0cdi1H!-+VS)(*" variabletype="">1</field></block></xml>',
        'blocksJson' => '{"code":[{"block":"VAR_GET","var_name":"my_1","comment":"","comment_id":"1"}],"_var":{"my_1":0},"_sp_var":{}}',
        'CPlusCode' => json_encode([$faker->sentence(), $faker->sentence(), $faker->sentence()]),
        'problems' => json_encode([[
            'name' => $faker->words(5, true),
            'link' => 'http://www.testOJ.com/' . rand(1, 1000000),
        ]]),
    ];
});
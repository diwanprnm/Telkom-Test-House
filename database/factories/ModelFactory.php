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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'id' => str_random(2),
        'role_id' =>1,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\CalibrationCharge::class, function (Faker\Generator $faker) {
    return [
        'device_name' => str_random(10),
        'price' => mt_rand(0,10000),
        'is_active' => mt_rand(0,1) 
    ];
});

$factory->define(App\Company::class, function (Faker\Generator $faker) {
    return [
    	'id' => bcrypt(str_random(10)),
        'name' => str_random(10),
        'address' => str_random(10),
        'city' => str_random(10) ,
        'email' => $faker->safeEmail,
        'postal_code' => str_random(10) ,
        'phone_number' =>mt_rand(0,10000) ,
        'fax' => str_random(10) ,
        'npwp_number' => mt_rand(0,10000) ,
        'npwp_file' => str_random(10) ,
        'siup_number' => str_random(10) ,
        'siup_file' => str_random(10), 
        'siup_date' => str_random(10), 
        'qs_certificate_number' => str_random(10) ,
        'qs_certificate_file' => str_random(10) ,
        'qs_certificate_date' => str_random(10), 
        'is_active' => mt_rand(0,1),
        'created_by' => mt_rand(0,1),
        'created_at' => mt_rand(0,1),
        'updated_by' => mt_rand(0,1),
        'updated_at' => mt_rand(0,1),
    ];
});

$factory->define(App\Feedback::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->numberBetween(1000,9000),
        'email' => $faker->safeEmail,
        'subject' => $faker->sentence(2, true),
        'message' => $faker->sentence(6, true),
        'created_by' => mt_rand(0,1),
        'created_at' => mt_rand(0,1),
        'updated_by' => mt_rand(0,1),
        'updated_at' => mt_rand(0,1),
        'status' => 0,
        'category' => $faker->word,

    ];
});

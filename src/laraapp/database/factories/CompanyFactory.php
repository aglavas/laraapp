<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(\App\Entities\Company::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'location' => $faker->country,
        'user_id' => factory(\App\Entities\User::class)->create()->id,
    ];
});

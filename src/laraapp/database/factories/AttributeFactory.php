<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Entities\Attribute::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});

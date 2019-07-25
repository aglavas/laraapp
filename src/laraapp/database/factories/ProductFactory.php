<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Entities\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'price' => $faker->randomFloat(2, 0.1, 5555),
        'qty' => $faker->numberBetween(1, 50),
        'company_id' => factory(\App\Entities\Company::class)->create()->id
    ];
});

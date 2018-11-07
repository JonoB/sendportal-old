<?php

use App\Models\Segment;
use Faker\Generator as Faker;

$factory->define(Segment::class, function (Faker $faker) {
    return [
        'name' => ucwords($faker->word)
    ];
});

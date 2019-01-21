<?php

use App\Models\Campaign;
use App\Models\Provider;
use Faker\Generator as Faker;

$factory->define(Campaign::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'subject' => $faker->title,
        'from_name' => $faker->name,
        'from_email' => $faker->email,
        'provider_id' => factory(Provider::class),
    ];
});

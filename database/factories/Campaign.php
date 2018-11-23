<?php

use App\Models\Campaign;
use App\Models\Provider;
use Faker\Generator as Faker;

$factory->define(Campaign::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'provider_id' => factory(Provider::class)->create()->id
    ];
});

<?php

use App\Models\Provider;
use App\Models\ProviderType;
use Faker\Generator as Faker;

$factory->define(Provider::class, function (Faker $faker) {
    return [
        'name' => ucwords($faker->word),
        'type_id' => $faker->randomElement(ProviderType::pluck('id'))
    ];
});

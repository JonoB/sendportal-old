<?php

use App\Models\Provider;
use Faker\Generator as Faker;

$factory->define(Provider::class, function (Faker $faker)
{
    return [
        'name' => 'Provider 1',
        'type_id' => 1,
        'settings' => [
            'test'
        ],
    ];
});

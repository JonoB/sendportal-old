<?php

use App\Models\Subscriber;
use Faker\Generator as Faker;

$factory->define(Subscriber::class, function (Faker $faker) {
    return [
        'hash' => $faker->uuid,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->safeEmail
    ];
});

$factory->state(Subscriber::class, 'segmented', function (Faker $faker) {
    return [];
});

$factory->afterCreatingState(Subscriber::class, 'segmented', function ($subscriber, $faker) {
    $subscriber->segments()->saveMany(factory(\App\Models\Segment::class, 2)->make());
});

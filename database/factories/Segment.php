<?php

use App\Models\Segment;
use Faker\Generator as Faker;

$factory->define(Segment::class, function (Faker $faker) {
    return [
        'name' => ucwords($faker->word)
    ];
});

$factory->state(Segment::class, 'subscribed', function (Faker $faker) {
    return [];
});

$factory->afterCreatingState(Segment::class, 'subscribed', function ($subscriber, $faker) {
    $subscriber->subscribers()->saveMany(factory(\App\Models\Subscriber::class, 2)->make());
});

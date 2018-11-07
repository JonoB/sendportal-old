<?php

use App\Models\Autoresponder;
use App\Models\Segment;
use Faker\Generator as Faker;

$factory->define(Autoresponder::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'segment_id' => factory(Segment::class)->create()->id,
    ];
});

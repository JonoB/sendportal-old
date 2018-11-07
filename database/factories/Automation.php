<?php

use App\Models\Automation;
use App\Models\Segment;
use Faker\Generator as Faker;

$factory->define(Automation::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'segment_id' => factory(Segment::class)->create()->id,
    ];
});

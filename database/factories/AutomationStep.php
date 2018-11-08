<?php

use App\Models\Automation;
use App\Models\AutomationStep;
use Faker\Generator as Faker;

$factory->define(AutomationStep::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'automation_id' => function ()
        {
            return factory(Automation::class)->create()->id;
        },
    ];
});

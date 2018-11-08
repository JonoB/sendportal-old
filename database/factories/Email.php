<?php

use App\Models\Campaign;
use App\Models\Email;
use Faker\Generator as Faker;

$factory->define(Email::class, function (Faker $faker)
{
    return [
        'mailable_id' => function ()
        {
            return factory(Campaign::class)->create()->id;
        },
        'mailable_type' => 'App\Models\Campaign',
    ];
});

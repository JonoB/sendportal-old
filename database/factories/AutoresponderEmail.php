<?php

use App\Models\Autoresponder;
use App\Models\AutoResponderEmail;
use Faker\Generator as Faker;

$factory->define(AutoresponderEmail::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'autoresponder_id' => factory(Autoresponder::class)->create()->id,
    ];
});

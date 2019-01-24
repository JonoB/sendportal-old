<?php

use App\Models\Campaign;
use App\Models\CampaignStatus;
use App\Models\Provider;
use App\Models\Template;
use Faker\Generator as Faker;

$factory->define(Campaign::class, function (Faker $faker)
{
    return [
        'name' => $faker->word,
        'subject' => $faker->title,
        'from_name' => $faker->name,
        'from_email' => $faker->email,
        'provider_id' => factory(Provider::class),
    ];
});

$factory->state(Campaign::class, 'withContent', function (Faker $faker)
{
    return [
        'content' => $faker->paragraph,
    ];
});

$factory->state(Campaign::class, 'withTemplate', function ()
{
    $template = factory(Template::class)->create();

    return [
        'template_id' => $template->id,
    ];
});

$factory->state(Campaign::class, 'sent', function ()
{
    return [
        'status_id' => CampaignStatus::STATUS_SENT,
    ];
});

$factory->state(Campaign::class, 'draft', function ()
{
    return [
        'status_id' => CampaignStatus::STATUS_DRAFT,
    ];
});

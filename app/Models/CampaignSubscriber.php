<?php

namespace App\Models;

class CampaignSubscriber extends BaseModel
{
    protected $table = 'campaign_subscriber';

    protected $fillable = [
        'campaign_id',
        'subscriber_id',
        'ip',
        'open_count',
        'click_count',
    ];
}

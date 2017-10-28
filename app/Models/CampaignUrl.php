<?php

namespace App\Models;

use App\Traits\Uuid;

class CampaignUrl extends BaseModel
{
    use Uuid;

    protected $fillable = [
        'campaign_id',
        'original_url',
    ];
}

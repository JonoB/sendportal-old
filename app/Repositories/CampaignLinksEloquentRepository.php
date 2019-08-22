<?php

namespace App\Repositories;

use App\Interfaces\CampaignLinksRepositoryInterface;
use App\Models\CampaignLink;

class CampaignLinksEloquentRepository extends BaseEloquentRepository
{
    protected $modelName = CampaignLink::class;
}

<?php

namespace App\Repositories;

use App\Interfaces\CampaignLinksRepositoryInterface;
use App\Models\CampaignLink;

class CampaignLinksEloquentRepository extends BaseEloquentRepository implements CampaignLinksRepositoryInterface
{
    protected $modelName = CampaignLink::class;
}

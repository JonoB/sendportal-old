<?php

namespace App\Repositories;

use App\Interfaces\CampaignRepositoryInterface;
use App\Models\Campaign;

class CampaignEloquentRepository extends BaseEloquentRepository implements CampaignRepositoryInterface
{
    protected $modelName = Campaign::class;
}

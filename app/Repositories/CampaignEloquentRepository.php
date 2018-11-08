<?php

namespace App\Repositories;

use App\Interfaces\CampaignRepositoryInterface;
use App\Models\Campaign;
use App\Models\CampaignStatus;

class CampaignEloquentRepository extends BaseEloquentRepository implements CampaignRepositoryInterface
{
    protected $modelName = Campaign::class;

    /**
     * @return mixed
     */
    public function queuedCampaigns()

    {
        return $this->getQueryBuilder()
            ->where('status_id', CampaignStatus::STATUS_QUEUED)
            ->with('segments')
            ->get();
    }
}

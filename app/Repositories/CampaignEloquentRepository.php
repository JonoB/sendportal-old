<?php

namespace App\Repositories;

use App\Interfaces\CampaignRepositoryInterface;
use App\Models\Campaign;
use App\Models\CampaignStatus;

class CampaignTenantRepository extends BaseTenantRepository implements CampaignRepositoryInterface
{
    protected $modelName = Campaign::class;

    /**
     * @return mixed
     */
    public function queuedCampaigns($teamId)
    {
        return $this->getQueryBuilder($teamId)
            ->where('status_id', CampaignStatus::STATUS_QUEUED)
            ->with('segments')
            ->get();
    }
}

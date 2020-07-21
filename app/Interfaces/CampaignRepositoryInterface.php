<?php

namespace App\Interfaces;

interface CampaignRepositoryInterface extends BaseTenantInterface
{
    /**
     * @return mixed
     */
    public function queuedCampaigns($teamId);
}

<?php

namespace App\Interfaces;

interface CampaignRepositoryInterface extends BaseEloquentInterface
{

    /**
     * @return mixed
     */
    public function queuedCampaigns();
}

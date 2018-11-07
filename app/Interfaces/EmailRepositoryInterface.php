<?php

namespace App\Interfaces;

interface EmailRepositoryInterface extends BaseEloquentInterface
{
    public function queuedCampaigns();
}

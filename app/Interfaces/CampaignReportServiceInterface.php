<?php

namespace App\Interfaces;

interface CampaignReportServiceInterface
{
    public function opensPerHour($campaignId);

    public function campaignLinks($campaignId);
}

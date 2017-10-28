<?php

namespace App\Interfaces;


use App\Models\Subscriber;
use App\Models\Campaign;

interface CampaignContentServiceInterface
{
    /**
     * @param Campaign $campaign
     */
    public function setCampaign(Campaign $campaign);

    /**
     * Merge open tracking image and subscriber tags into the content
     *
     * @param Subscriber $subscriber
     * @return string
     */
    public function getMergedContent(Subscriber $subscriber);
}

<?php

namespace App\Interfaces;

use App\Models\Subscriber;
use App\Models\Campaign;

interface GenerateOpenTrackingImageInterface
{
    /**
     * Generate the tracking image for emails
     *
     * @param string $campaignId
     * @param string $subscriberId
     * @return mixed
     */
    public function generate($campaignId, $subscriberId);
}

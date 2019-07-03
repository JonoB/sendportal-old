<?php

namespace App\Services;

use App\Interfaces\GenerateOpenTrackingImageInterface;
use App\Models\Subscriber;
use App\Models\Campaign;

class GenerateOpenTrackingImageService implements GenerateOpenTrackingImageInterface
{
    /**
     * Generate the tracking image for steps
     *
     * @param string $campaignId
     * @param string $subscriberId
     * @return mixed
     */
    public function generate($campaignId, $subscriberId)
    {
        $route = $this->generateRoute($campaignId, $subscriberId);

        return '<img src="' . $route . '" alt="" />';
    }

    /**
     * Generate the tracking route
     *
     * @param string $campaignId
     * @param string $subscriberId
     * @return string
     */
    protected function generateRoute($campaignId, $subscriberId)
    {
        return route('tracker.opens', [$campaignId, $subscriberId]);
    }
}

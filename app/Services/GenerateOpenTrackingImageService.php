<?php

namespace App\Services;

use App\Interfaces\GenerateOpenTrackingImageInterface;
use App\Models\Subscriber;
use App\Models\Newsletter;

class GenerateOpenTrackingImageService implements GenerateOpenTrackingImageInterface
{
    /**
     * Generate the tracking image for emails
     *
     * @param string $newsletterId
     * @param string $subscriberId
     * @return mixed
     */
    public function generate($newsletterId, $subscriberId)
    {
        $route = $this->generateRoute($newsletterId, $subscriberId);

        return '<img src="' . $route . '" alt="" />';
    }

    /**
     * Generate the tracking route
     *
     * @param string $newsletterId
     * @param string $subscriberId
     * @return string
     */
    protected function generateRoute($newsletterId, $subscriberId)
    {
        return route('tracker.opens', [$newsletterId, $subscriberId]);
    }
}

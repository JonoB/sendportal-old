<?php

namespace App\Interfaces;

use App\Models\Subscriber;
use App\Models\Newsletter;

interface GenerateOpenTrackingImageInterface
{
    /**
     * Generate the tracking image for emails
     *
     * @param string $newsletterId
     * @param string $subscriberId
     * @return mixed
     */
    public function generate($newsletterId, $subscriberId);
}

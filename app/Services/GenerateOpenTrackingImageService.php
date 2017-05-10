<?php

namespace App\Services;

use App\Interfaces\GenerateOpenTrackingImageInterface;
use App\Models\Contact;
use App\Models\Newsletter;

class GenerateOpenTrackingImageService implements GenerateOpenTrackingImageInterface
{
    /**
     * Generate the tracking image for emails
     *
     * @param string $newsletterId
     * @param string $contactId
     * @return mixed
     */
    public function generate($newsletterId, $contactId)
    {
        $route = $this->generateRoute($newsletterId, $contactId);

        return '<img src="' . $route . '" alt="" />';
    }

    /**
     * Generate the tracking route
     *
     * @param string $newsletterId
     * @param string $contactId
     * @return string
     */
    protected function generateRoute($newsletterId, $contactId)
    {
        return route('tracker.opens', [$newsletterId, $contactId]);
    }
}

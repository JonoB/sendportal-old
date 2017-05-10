<?php

namespace App\Interfaces;

use App\Models\Contact;
use App\Models\Newsletter;

interface GenerateOpenTrackingImageInterface
{
    /**
     * Generate the tracking image for emails
     *
     * @param string $newsletterId
     * @param string $contactId
     * @return mixed
     */
    public function generate($newsletterId, $contactId);
}

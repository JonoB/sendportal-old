<?php

namespace App\Interfaces;

use App\Models\Subscriber;
use App\Models\Campaign;

interface CampaignDispatchInterface
{
    /**
     * Send the campaign
     *
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     * @return mixed
     */
    public function send($fromEmail, $toEmail, $subject, $content);
}

<?php

namespace App\Interfaces;

interface DeliveryDispatchInterface
{
    /**
     * Send the campaign
     *
     * @param string $mailService
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     * @return mixed
     */
    public function send($mailService, $fromEmail, $toEmail, $subject, $content);
}

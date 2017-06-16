<?php

namespace App\Interfaces;

interface SesServiceInterface
{

    /**
     * @param $fromEmail
     * @param array $toEmail
     * @param $subject
     * @param $content
     * @return \Aws\Result
     */
    public function sendMail($fromEmail, array $toEmail, $subject, $content);
}
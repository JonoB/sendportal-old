<?php

namespace App\Interfaces;

interface MailAdapterInterface
{
    /**
     * Send the e-mail
     *
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     * @return mixed
     */
    public function send($fromEmail, $toEmail, $subject, $content);
}

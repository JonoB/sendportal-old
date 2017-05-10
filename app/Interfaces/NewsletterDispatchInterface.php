<?php

namespace App\Interfaces;

use App\Models\Contact;
use App\Models\Newsletter;

interface NewsletterDispatchInterface
{
    /**
     * Send the newsletter
     *
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     * @return mixed
     */
    public function send($fromEmail, $toEmail, $subject, $content);
}

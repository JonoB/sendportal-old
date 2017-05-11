<?php

namespace App\Interfaces;

interface NewsletterReportServiceInterface
{
    public function opensPerHour($newsletterId);

    public function newsletterUrls($newsletterId);

}

<?php

namespace App\Interfaces;


use App\Models\Subscriber;
use App\Models\Newsletter;

interface NewsletterContentServiceInterface
{
    /**
     * @param Newsletter $newsletter
     */
    public function setNewsletter(Newsletter $newsletter);

    /**
     * Merge open tracking image and subscriber tags into the content
     *
     * @param Subscriber $subscriber
     * @return string
     */
    public function getMergedContent(Subscriber $subscriber);
}

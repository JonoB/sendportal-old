<?php

namespace App\Interfaces;


use App\Models\Contact;
use App\Models\Newsletter;

interface NewsletterContentServiceInterface
{
    /**
     * @param Newsletter $newsletter
     */
    public function setNewsletter(Newsletter $newsletter);

    /**
     * Merge open tracking image and contact tags into the content
     *
     * @param Contact $contact
     * @return string
     */
    public function getMergedContent(Contact $contact);
}

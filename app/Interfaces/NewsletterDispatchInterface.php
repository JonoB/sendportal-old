<?php

namespace App\Interfaces;

use App\Models\Contact;
use App\Models\Newsletter;

interface NewsletterDispatchInterface
{
    /**
     * Invoke the send method
     *
     * @param Newsletter $newsletter
     * @param Contact $contact
     * @return \Aws\Result|bool
     */
    public function send(Newsletter $newsletter, Contact $contact);
}

<?php

namespace App\Interfaces;

use App\Models\Contact;
use App\Models\Newsletter;

interface GenerateOpenTrackingImageInterface
{
    public function generate(Newsletter $newsletter, Contact $contact);
}

<?php

namespace App\Services;

use App\Interfaces\GenerateOpenTrackingImageInterface;
use App\Models\Contact;
use App\Models\Newsletter;

class GenerateOpenTrackingImageService implements GenerateOpenTrackingImageInterface
{
    public function generate(Newsletter $newsletter, Contact $contact)
    {
        $route = $this->generateRoute($newsletter, $contact);

        return '<img src="' . $route . '" alt="" />';
    }

    protected function generateRoute($newsletter, $contact)
    {
        return route('tracker.opens', [$newsletter, $contact]);
    }
}

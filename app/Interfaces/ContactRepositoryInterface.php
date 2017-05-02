<?php

namespace App\Interfaces;

use App\Models\Contact;

interface ContactRepositoryInterface extends BaseEloquentInterface
{
    /**
     * Sync segments to a contact
     *
     * @param Contact $contact
     * @param array $segments
     * @return mixed
     */
    public function syncSegments(Contact $contact, array $segments = []);
}

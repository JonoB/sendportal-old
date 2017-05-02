<?php

namespace App\Repositories;

use App\Interfaces\ContactRepositoryInterface;
use App\Models\Contact;

class ContactEloquentRepository extends BaseEloquentRepository implements ContactRepositoryInterface
{
    protected $modelName = Contact::class;

    /**
     * Sync segments to a contact
     *
     * @param Contact $contact
     * @param array $segments
     * @return mixed
     */
    public function syncSegments(Contact $contact, array $segments = [])
    {
        return $contact->segments()->sync($segments);
    }
}

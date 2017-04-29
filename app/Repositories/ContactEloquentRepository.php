<?php

namespace App\Repositories;

use App\Interfaces\ContactRepositoryInterface;
use App\Models\Contact;

class ContactEloquentRepository extends BaseEloquentRepository implements ContactRepositoryInterface
{
    protected $modelName = Contact::class;
}
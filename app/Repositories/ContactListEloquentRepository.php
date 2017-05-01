<?php

namespace App\Repositories;

use App\Interfaces\ContactListRepositoryInterface;
use App\Models\ContactList;

class ContactListEloquentRepository extends BaseEloquentRepository implements ContactListRepositoryInterface
{
    protected $modelName = ContactList::class;
}
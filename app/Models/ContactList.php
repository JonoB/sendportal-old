<?php

namespace App\Models;

class ContactList extends BaseModel
{
    protected $table = 'lists';

    protected $fillable = [
        'name',
    ];
}
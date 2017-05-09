<?php

namespace App\Models;

class ContactNewsletter extends BaseModel
{
    protected $fillable = [
        'contact_id',
        'newsletter_id',
        'ip',
        'open_count',
        'click_count',
    ];
}

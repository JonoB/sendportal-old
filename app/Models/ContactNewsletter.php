<?php

namespace App\Models;

class ContactNewsletter extends BaseModel
{
    protected $table = 'contact_newsletter';

    protected $fillable = [
        'contact_id',
        'newsletter_id',
        'ip',
        'open_count',
        'click_count',
    ];
}

<?php

namespace App\Models;

class NewsletterOpen extends BaseModel
{
    protected $fillable = [
        'contact_id',
        'newsletter_id',
        'counter',
    ];
}

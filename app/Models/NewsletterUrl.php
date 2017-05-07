<?php

namespace App\Models;

class NewsletterUrl extends BaseModel
{
    protected $fillable = [
        'newsletter_id',
        'original_url',
    ];
}

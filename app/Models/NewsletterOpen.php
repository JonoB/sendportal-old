<?php

namespace App\Models;

use App\Traits\Uuid;

class NewsletterOpen extends BaseModel
{
    use Uuid;

    protected $fillable = [
        'contact_id',
        'newsletter_id',
        'counter',
    ];
}

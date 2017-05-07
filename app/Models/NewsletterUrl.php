<?php

namespace App\Models;

use App\Traits\Uuid;

class NewsletterUrl extends BaseModel
{
    use Uuid;

    protected $fillable = [
        'newsletter_id',
        'original_url',
    ];
}

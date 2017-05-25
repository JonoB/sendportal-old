<?php

namespace App\Models;

class NewsletterSubscriber extends BaseModel
{
    protected $table = 'newsletter_subscriber';

    protected $fillable = [
        'subscriber_id',
        'newsletter_id',
        'ip',
        'open_count',
        'click_count',
    ];
}

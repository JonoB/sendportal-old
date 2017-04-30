<?php

namespace App\Models;

class Newsletter extends BaseModel
{
    protected $fillable = [
        'template_id',
        'newsletter_status_id',
        'name',
        'subject',
        'content',
        'from_name',
        'from_email',
        'track_opens',
        'track_clicks',
        'scheduled_at',
    ];

    protected $booleanFields = [
        'track_opens',
        'track_clicks',
    ];
}

<?php

namespace App\Models;

class Newsletter extends BaseModel
{
    const STATUS_DRAFT = 1;
    const STATUS_SENDING = 2;
    const STATUS_SENT = 3;

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

    public function template()
    {
        return $this->belongsTo(Template::class)
            ->select('id', 'name');
    }
}

<?php

namespace App\Models;

class Newsletter extends BaseModel
{
    protected $fillable = [
        'template_id',
        'status_id',
        'name',
        'subject',
        'content',
        'from_name',
        'from_email',
        'track_opens',
        'track_clicks',
        'sent_count',
        'open_count',
        'scheduled_at',
    ];

    // we can't use boolean fields on this model because
    // we have multiple points to update from the controller
    protected $booleanFields = [];

    public function getOpenRatioAttribute()
    {
        if ($this->attributes['sent_count'])
        {
            return $this->attributes['open_count'] / $this->attributes['sent_count'];
        }

        return 0;
    }

    public function segments()
    {
        return $this->belongsToMany(Segment::class);
    }

    public function status()
    {
        return $this->belongsTo(NewsletterStatus::class, 'status_id');
    }

    public function template()
    {
        return $this->belongsTo(Template::class)
            ->select('id', 'name');
    }


}

<?php

namespace App\Models;

use App\Traits\Uuid;

class Newsletter extends BaseModel
{
    use Uuid;

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
        'click_count',
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

    public function getClickRatioAttribute()
    {
        if ($this->attributes['click_count'])
        {
            return $this->attributes['click_count'] / $this->attributes['sent_count'];
        }

        return 0;
    }

    /**
     * Lists this newsletter was sent to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lists()
    {
        return $this->belongsToMany(SubscriberList::class)->withTimestamps();
    }

    /**
     * Status of the newsletter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(NewsletterStatus::class, 'status_id');
    }

    /**
     * Template the newsletter uses
     *
     * @return mixed
     */
    public function template()
    {
        return $this->belongsTo(Template::class)
            ->select('id', 'name');
    }
}

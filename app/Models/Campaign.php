<?php

namespace App\Models;

use App\Traits\Uuid;

class Campaign extends BaseModel
{
    use Uuid;

    protected $fillable = [
        'name',
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
     * The email associated to this campaign
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function email()
    {
        return $this->morphMany(Email::class, 'mailable');
    }

    /**
     * Lists this campaign was sent to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function segments()
    {
        return $this->belongsToMany(Segment::class)->withTimestamps();
    }

    /**
     * Status of the campaign
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(CampaignStatus::class, 'status_id');
    }

    /**
     * Template the campaign uses
     *
     * @return mixed
     */
    public function template()
    {
        return $this->belongsTo(Template::class)
            ->select('id', 'name');
    }
}

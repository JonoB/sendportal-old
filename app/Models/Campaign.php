<?php

namespace App\Models;

class Campaign extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    // we can't use boolean fields on this model because
    // we have multiple points to update from the controller
    protected $booleanFields = [];


    /**
     * The email associated to this campaign
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function email()
    {
        return $this->morphOne(Email::class, 'mailable');
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
}

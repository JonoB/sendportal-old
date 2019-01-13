<?php

namespace App\Models;

class Campaign extends BaseModel
{
    protected $guarded = [];

    // we can't use boolean fields on this model because
    // we have multiple points to update from the controller
    protected $booleanFields = [];

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
     * Provider relationship method
     *
     * @param null
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}

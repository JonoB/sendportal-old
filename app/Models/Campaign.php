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
        return $this->belongsTo(CampaignStatus::class);
    }

    /**
     * Campaign template
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
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

    /**
     * Get the full content for this email, including the template content
     *
     * @return string
     */
    public function getFullContentAttribute(): string
    {
        return $this->template_id ?
            str_replace('{{content}}', $this->content, $this->template->content) :
            $this->content;
    }

    /**
     * Determine whether the campaign is a draft.
     *
     * @return bool
     */
    public function getDraftAttribute(): bool
    {
        return $this->status_id === CampaignStatus::STATUS_DRAFT;
    }

    /**
     * Determine whether the campaign has been sent.
     *
     * @return bool
     */
    public function getSentAttribute(): bool
    {
        return $this->status_id === CampaignStatus::STATUS_SENT;
    }
}

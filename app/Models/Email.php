<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $guarded = [];

    /**
     * Get the email's open ratio as an attribute.
     *
     * @return float|int
     */
    public function getOpenRatioAttribute()
    {
        if ($this->attributes['sent_count'])
        {
            return $this->attributes['open_count'] / $this->attributes['sent_count'];
        }

        return 0;
    }

    /**
     * Get the email's click ratio as an attribute.
     *
     * @return float|int
     */
    public function getClickRatioAttribute()
    {
        if ($this->attributes['click_count'])
        {
            return $this->attributes['click_count'] / $this->attributes['sent_count'];
        }

        return 0;
    }

    /**
     * The mailable relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function mailable()
    {
        return $this->morphTo();
    }

    /**
     * The email's status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(CampaignStatus::class);
    }

    /**
     * The email's template.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}

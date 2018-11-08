<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends BaseModel
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * Emails using this template
     *
     * @return HasMany
     */
    public function emails(): HasMany
    {
        return $this->hasMany(Email::class);
    }

    /**
     * Whether the template is currently being used by any emails
     *
     * @return bool
     */
    public function getIsInUseAttribute(): bool
    {
        return $this->emails()->count() > 0;
    }
}

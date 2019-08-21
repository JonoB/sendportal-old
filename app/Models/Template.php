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
}

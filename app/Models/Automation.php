<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Automation extends BaseModel
{
    protected $guarded = [];

    /**
     * Get all automation steps
     *
     * @return HasMany
     */
    public function automation_steps(): ?HasMany
    {
        return $this->hasMany(AutomationStep::class)->orderBy('delay_seconds');
    }

    /**
     * Return the first automation step
     *
     * @return AutomationStep|null
     */
    public function first_automation_step(): ?HasOne
    {
        return $this->hasOne(AutomationStep::class)
            ->orderBy('delay_seconds');
    }
}

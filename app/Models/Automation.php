<?php

namespace App\Models;

class Automation extends BaseModel
{
    protected $guarded = [];

    public function emails()
    {
        return $this->morphMany(Email::class, 'mailable');
    }

    public function automation_steps()
    {
        return $this->hasMany(AutomationStep::class)->orderBy('delay_seconds');
    }
}

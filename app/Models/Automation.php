<?php

namespace App\Models;

class Automation extends BaseModel
{
    protected $guarded = [];

    public function steps()
    {
        return $this->hasMany(AutomationStep::class);
    }

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }

    public function emails()
    {
        return $this->morphMany(Email::class, 'mailable');
    }
}

<?php

namespace App\Models;

class Automation extends BaseModel
{
    protected $guarded = [];

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }

    public function emails()
    {
        return $this->morphMany(Email::class, 'mailable');
    }
}

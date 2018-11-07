<?php

namespace App\Models;

class Automation extends BaseModel
{
    protected $guarded = [];

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }
}

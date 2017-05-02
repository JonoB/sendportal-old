<?php

namespace App\Models;

class Contact extends BaseModel
{
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
    ];

    public function segments()
    {
        return $this->belongsToMany(Segment::class);
    }
}

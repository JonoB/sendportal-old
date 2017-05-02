<?php

namespace App\Models;

class Segment extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    public function contacts()
    {
        return $this->belongsToMany(Contact::class);
    }
}

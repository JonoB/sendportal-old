<?php

namespace App\Models;

use App\Traits\Uuid;

class Contact extends BaseModel
{
    use Uuid;

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

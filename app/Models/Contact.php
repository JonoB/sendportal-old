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
        'unsubscribed',
    ];

    protected $booleanFields = [
        'unsubscribed',
    ];

    public function segments()
    {
        return $this->belongsToMany(Segment::class);
    }
}

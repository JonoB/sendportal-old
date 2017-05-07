<?php

namespace App\Models;

use App\Traits\Uuid;

class Template extends BaseModel
{
    use Uuid;

    protected $fillable = [
        'name',
        'content',
    ];
}
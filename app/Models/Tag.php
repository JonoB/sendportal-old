<?php namespace App\Models;

use App\Traits\Uuid;

class Tag extends BaseModel
{
    use Uuid;

    protected $fillable = [
        'name',
    ];
}

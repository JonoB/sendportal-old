<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Autoresponder extends BaseModel
{
    use Uuid;

    protected $guarded = [];

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }
}

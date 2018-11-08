<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationStep extends Model
{
    protected $guarded = [];

    public function automation()
    {
        return $this->belongsTo(Automation::class);
    }

    public function email()
    {
        return $this->morphOne(Email::class, 'mailable');
    }
}

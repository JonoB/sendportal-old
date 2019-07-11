<?php

namespace App\Models;

class AutomationSchedule extends BaseModel
{
    protected $guarded = [];

    public function automation_step()
    {
        return $this->belongsTo(AutomationStep::class);
    }

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }
}

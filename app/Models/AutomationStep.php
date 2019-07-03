<?php

namespace App\Models;

class AutomationStep extends BaseModel
{
    protected static $frequencies = [
        'minutes' => 'Minute(s)',
        'hours' => 'Hour(s)',
        'days' => 'Day(s)',
    ];

    protected $guarded = [];

    public static function listFrequencies()
    {
        return self::$frequencies;
    }

    public function getDelayStringAttribute($frequency)
    {
        return $this->delay . ' ' . array_get(self::listFrequencies(), $frequency);
    }

    public function automation()
    {
        return $this->belongsTo(Automation::class);
    }
}

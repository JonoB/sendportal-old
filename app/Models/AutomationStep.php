<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationStep extends Model
{
    const UNIT_HOURS = 0;
    const UNIT_DAYS = 1;

    public static $units = [
        self::UNIT_HOURS => 'Hours',
        self::UNIT_DAYS => 'Days',
    ];

    protected $guarded = [];

    /**
     * The automation relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function automation()
    {
        return $this->belongsTo(Automation::class);
    }

    /**
     * The email relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function email()
    {
        return $this->morphOne(Email::class, 'mailable');
    }

    /**
     * Determine whether or not an automation step is delayed.
     *
     * @return bool
     */
    public function getIsDelayedAttribute()
    {
        return $this->delay !== null;
    }

    public function getSendsAttribute()
    {
        if($this->is_delayed)
        {
            $unit = self::$units[$this->delay_unit];
            return "After {$this->delay} {$unit}";
        }
        else
        {
            return 'Immediately';
        }
    }
}

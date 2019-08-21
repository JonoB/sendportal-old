<?php

namespace App\Models;

class AutomationStep extends BaseModel
{
    protected $guarded = [];

    protected static $frequencies = [
        'minutes' => 'Minute(s)',
        'hours' => 'Hour(s)',
        'days' => 'Day(s)',
    ];

    public static function boot()
    {
        parent::boot();

        self::saving(function($model) {

            switch ($model->delay_type)
            {
                case 'minutes':
                    $model->delay_seconds = $model->delay * 60;
                    break;

                case 'hours':
                    $model->delay_seconds = $model->delay * 60 * 60;
                    break;

                case 'days':
                    $model->delay_seconds = $model->delay * 60 * 60 * 24;
                    break;
            }
        });
    }

    public static function listFrequencies()
    {
        return self::$frequencies;
    }

    public function getDelayStringAttribute()
    {
        return $this->delay . ' ' . array_get(self::listFrequencies(), $this->delay_type);
    }

    public function automation()
    {
        return $this->belongsTo(Automation::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}

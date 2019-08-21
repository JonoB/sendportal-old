<?php

namespace App\Traits;

use Carbon\Carbon;

trait ScheduledAt
{
    protected function calculateNextScheduledAt(Carbon $timestamp, $delayInSeconds)
    {
        return Carbon::parse($timestamp)->addSeconds($delayInSeconds);
    }
}
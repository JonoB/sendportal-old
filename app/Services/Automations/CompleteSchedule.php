<?php

namespace App\Services\Automations;

use App\Models\AutomationSchedule;

class CompleteSchedule
{
    /**
     * Mark the schedule as complete in the database
     *
     * @param AutomationSchedule $schedule
     * @return AutomationSchedule
     */
    public function handle(AutomationSchedule $schedule, $next)
    {
        $this->markScheduleAsComplete($schedule);

        return $next($schedule);
    }

    /**
     * Execute the database query
     *
     * @param AutomationSchedule $schedule
     *
     * @return void
     */
    protected function markScheduleAsComplete(AutomationSchedule $schedule): void
    {
        $schedule->completed_at = now();
        $schedule->save();
    }
}
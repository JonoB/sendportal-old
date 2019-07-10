<?php

namespace App\Services\Automations;

use App\Models\AutomationSchedule;

class StartSchedule
{
    /**
     * Mark the schedule as started in the database
     *
     * @param AutomationSchedule $schedule
     * @return AutomationSchedule
     */
    public function handle(AutomationSchedule $schedule, $next)
    {
        $this->markScheduleAsStarted($schedule);

        return $next($schedule);
    }

    /**
     * Execute the database request
     *
     * @param AutomationSchedule $schedule
     * @return AutomationSchedule
     */
    protected function markScheduleAsStarted(AutomationSchedule $schedule)
    {
        $schedule->started_at = now();
        $schedule->save();

        return $schedule;
    }
}
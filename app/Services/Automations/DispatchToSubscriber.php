<?php

namespace App\Services\Automations;

use App\Models\AutomationSchedule;

class DispatchToSubscriber
{
    /**
     * Check that the subscriber is still active
     * 
     * @param AutomationSchedule $schedule
     * @return AutomationSchedule
     */
    public function handle(AutomationSchedule $schedule, $next)
    {
        if ($schedule->completed_at)
        {
            return $next($schedule);
        }

        $this->dispatch($schedule);

        return $next($schedule);
    }

    /**
     * Dispatch the content
     *
     * @param AutomationSchedule $schedule
     * @return AutomationSchedule
     */
    protected function dispatch(AutomationSchedule $schedule)
    {
        // do something
    }
}
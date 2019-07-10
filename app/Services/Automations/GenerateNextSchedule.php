<?php

namespace App\Services\Automations;

use App\Models\AutomationSchedule;
use App\Models\AutomationStep;
use App\Models\Subscriber;
use Carbon\Carbon;
use Closure;

class GenerateNextSchedule
{
    /**
     * Create the next automation step if there is one
     *
     * @param AutomationSchedule $schedule
     * @param \Closure $next
     * @return mixed
     */
    public function handle(AutomationSchedule $schedule, Closure $next)
    {
        if ($nextAutomationStep = $this->getNextAutomationStep($schedule))
        {
            $this->createNextSchedule($schedule, $nextAutomationStep);
        }

        return $next($schedule);
    }

    /**
     * Find the next automation step
     *
     * @param AutomationSchedule $schedule
     * @return mixed
     */
    protected function getNextAutomationStep(AutomationSchedule $schedule)
    {
        return AutomationStep::orderBy('delay_seconds')
            ->where('automation_id', $schedule->automation_step->automation_id)
            ->where('delay_seconds', '>=', $schedule->automation_step->delay_seconds)
            ->where('id', '!=', $schedule->automation_step_id)
            ->first();
    }

    /**
     * Create the next automation schedule in the database
     *
     * @param AutomationSchedule $schedule
     * @param AutomationStep $nextAutomationStep
     * @return mixed
     */
    protected function createNextSchedule(AutomationSchedule $schedule, AutomationStep $nextAutomationStep)
    {
        $subscriber = Subscriber::find($schedule->subscriber_id);
        $nextScheduledAt = Carbon::parse($subscriber->created_at)->addSeconds($nextAutomationStep->delay_seconds);

        return AutomationSchedule::create([
            'subscriber_id' => $schedule->subscriber_id,
            'automation_step_id' => $nextAutomationStep->id,
            'scheduled_at' => $nextScheduledAt,
        ]);
    }
}
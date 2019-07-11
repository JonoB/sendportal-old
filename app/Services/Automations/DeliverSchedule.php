<?php

namespace App\Services\Automations;

use App\Models\AutomationSchedule;
use App\Models\Delivery;

class DeliverSchedule
{
    /**
     * Check that the subscriber is still active
     * 
     * @param AutomationSchedule $schedule
     * @return AutomationSchedule
     */
    public function handle(AutomationSchedule $schedule, $next)
    {
        $this->dispatch($schedule);

        return $next($schedule);
    }

    /**
     * Dispatch the content
     *
     * @param AutomationSchedule $schedule
     */
    protected function dispatch(AutomationSchedule $schedule): void
    {
        Delivery::create([
            'subscriber_id' => $schedule->subscriber_id,
            'source' => 'automation',
            'source_id' => $schedule->id,
            'recipient_email' => $schedule->subscriber->email,
            'subject' => $schedule->automation_step->subject,
            'from_name' => $schedule->automation_step->automation->from_name,
            'from_email' => $schedule->automation_step->automation->from_email,
            'sent_at' => null,
        ]);
    }
}
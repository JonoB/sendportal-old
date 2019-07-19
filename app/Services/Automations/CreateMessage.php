<?php

namespace App\Services\Automations;

use App\Models\AutomationSchedule;
use App\Models\Message;

class CreateMessage
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
        Message::create([
            'subscriber_id' => $schedule->subscriber_id,
            'source' => AutomationSchedule::class,
            'source_id' => $schedule->id,
            'recipient_email' => $schedule->subscriber->email,
            'subject' => $schedule->automation_step->subject,
            'from_name' => $schedule->automation_step->automation->from_name,
            'from_email' => $schedule->automation_step->automation->from_email,
            'sent_at' => null,
        ]);
    }
}
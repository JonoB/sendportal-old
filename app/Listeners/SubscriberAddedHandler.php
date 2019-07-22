<?php

namespace App\Listeners;

use App\Events\SubscriberAddedEvent;
use App\Models\Automation;
use App\Models\AutomationSchedule;
use App\Models\AutomationStep;
use App\Models\Subscriber;
use App\Traits\ScheduledAt;
use Illuminate\Support\Collection;

class SubscriberAddedHandler
{
    use ScheduledAt;

    /**
     * Handle the event.
     *
     * @param SubscriberAddedEvent $event
     * @return void
     */
    public function handle(SubscriberAddedEvent $event)
    {
        if ( ! $automations = $this->getAutomations())
        {
            return;
        }

        foreach ($automations as $automation)
        {
            if ( ! $automation->first_automation_step)
            {
                continue;
            }

            $this->createFirstAutomationSchedule($event->subscriber, $automation->first_automation_step);
        }
    }

    /**
     * Get all automations
     *
     * @return Collection|null
     */
    protected function getAutomations(): ?Collection
    {
        return Automation::with('first_automation_step')->get();
    }

    /**
     * Create the first automation step for this subscriber
     *
     * @param Subscriber $subscriber
     * @param AutomationStep $automationStep
     */
    protected function createFirstAutomationSchedule(Subscriber $subscriber, AutomationStep $automationStep): void
    {
        AutomationSchedule::firstOrCreate(
            [
                'subscriber_id' => $subscriber->id,
                'automation_step_id' => $automationStep->id
            ],
            [
                'scheduled_at' => $this->calculateNextScheduledAt($subscriber->created_at, $automationStep->delay_seconds),
            ]
        );
    }
}

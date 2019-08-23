<?php

namespace App\Listeners;

use App\Events\SubscriberAddedEvent;
use App\Models\AutomationSchedule;
use App\Models\AutomationStep;
use App\Models\Subscriber;
use App\Repositories\AutomationTenantRepository;
use App\Traits\ScheduledAt;
use Illuminate\Support\Collection;

class SubscriberAddedHandler
{
    use ScheduledAt;

    /**
     * @var AutomationTenantRepository
     */
    protected $automationRepository;

    public function __construct(AutomationTenantRepository $automationRepository)
    {
        $this->automationRepository = $automationRepository;
    }

    /**
     * Handle the event
     *
     * @param SubscriberAddedEvent $event
     * @throws \Exception
     */
    public function handle(SubscriberAddedEvent $event)
    {
        if ( ! $automations = $this->getAutomations($event->subscriber->team_id))
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
     * @param int $teamId
     * @return Collection|null
     * @throws \Exception
     */
    protected function getAutomations(int $teamId): ?Collection
    {
        return $this->automationRepository->all($teamId, 'id', ['automation_steps']);
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

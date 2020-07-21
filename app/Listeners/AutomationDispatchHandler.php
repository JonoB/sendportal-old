<?php

namespace App\Listeners;

use App\Events\AutomationDispatchEvent;
use App\Models\AutomationSchedule;
use App\Services\Automations\CompleteSchedule;
use App\Services\Automations\CreateMessage;
use App\Services\Automations\GenerateNextSchedule;
use App\Services\Automations\ValidateSubscriber;
use App\Services\Automations\StartSchedule;
use Illuminate\Pipeline\Pipeline;

class AutomationDispatchHandler
{
    /**
     * Handle the event.
     *
     * @param  AutomationDispatchEvent  $event
     * @return void
     */
    public function handle(AutomationDispatchEvent $event)
    {
        // check if the schedule still exists
        if ( ! $schedule = $this->findSchedule($event->automationSchedule->id))
        {
            return;
        }

        if ($schedule->started_at)
        {
            return;
        }

        $pipes = [
            StartSchedule::class,
            ValidateSubscriber::class,
            CreateMessage::class,
            GenerateNextSchedule::class,
            CompleteSchedule::class,
        ];

        try
        {
            app(Pipeline::class)
                ->send($schedule)
                ->through($pipes)
                ->then(function($schedule) {
                    return $schedule;
                });
        }
        catch (\Exception $exception)
        {
            \Log::error('Unable to dispatch schedule:' . $schedule->id . ':' . $exception->getMessage());
        }
    }

    /**
     * Find a single automation schedule
     *
     * @param int $id
     * @return AutomationSchedule|null
     */
    protected function findSchedule(int $id): ?AutomationSchedule
    {
        return AutomationSchedule::with('automation_step')->find($id);
    }
}

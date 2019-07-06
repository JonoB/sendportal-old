<?php

namespace App\Listeners;

use App\Events\AutomationDispatch;
use App\Models\AutomationSchedule;
use App\Models\AutomationStep;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AutomationDispatchHandler
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AutomationDispatch  $event
     * @return void
     */
    public function handle(AutomationDispatch $event)
    {
        if ( ! $schedule = $this->findSchedule($event->automationSchedule->id))
        {
            return;
        }

        if ($schedule->started_at)
        {
            return;
        }

        $this->markScheduleAsStarted($schedule);

        $subscriber = $this->findSubscriber($schedule->subscriber_id);

        // if the subscriber has unsubscribed, then we'll mark this schedule as complete
        // and not create another schedule
        if ($subscriber->unsubscribed_at)
        {
            $this->markScheduleAsComplete($schedule);

            return;
        }

        $this->dispatchEmail($schedule);

        if ($nextAutomationStep = $this->getNextAutomationStep($schedule))
        {
            $this->createNextSchedule($schedule, $nextAutomationStep);
        }

        $this->markScheduleAsComplete($schedule);
    }

    protected function dispatchEmail(AutomationSchedule $schedule)
    {
        // @todo
    }

    protected function findSchedule(int $id)
    {
        return AutomationSchedule::with('automation_step')->find($id);
    }

    protected function findSubscriber($id)
    {
        return Subscriber::find($id);
    }

    /**
     * Note that the next step may have the same delay_seconds, and we still want
     * to make sure that it gets selected it for the next run
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

    protected function markScheduleAsStarted(AutomationSchedule $schedule)
    {
        $schedule->started_at = now();
        $schedule->save();
    }

    protected function markScheduleAsComplete(AutomationSchedule $schedule)
    {
        $schedule->completed_at = now();
        $schedule->save();
    }
}

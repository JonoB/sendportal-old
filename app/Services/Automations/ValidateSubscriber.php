<?php

namespace App\Services\Automations;

use App\Models\AutomationSchedule;
use App\Models\Subscriber;

class ValidateSubscriber
{
    /**
     * Check that the subscriber is still active
     *
     * @param AutomationSchedule $schedule
     * @param $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(AutomationSchedule $schedule, $next)
    {
        if ( ! $subscriber = $this->findSubscriber($schedule->subscriber_id))
        {
            throw new \Exception('Unable to find a subscriber', $schedule);
        }

        // if the subscriber has already unsubscribed, then we'll mark this
        // schedule as complete. By throwing an exception, we'll ensure that
        // no further pipes are acted upon and we'll not create another schedule
        // for this schedule
        if ($subscriber->unsubscribed_at)
        {
            $this->markScheduleAsComplete($schedule);

            throw new \Exception('Subscriber is not subscribed:' . $subscriber->id);
        }

        return $next($schedule);
    }

    /**
     * Find the subscriber from the DB. We refresh this to get a live object,
     * instead of relying on the relationship which may have been eager loaded.
     *
     * @param $id
     * @return mixed
     */
    protected function findSubscriber($id): ?Subscriber
    {
        return Subscriber::find($id);
    }

    /**
     * Mark the schedule as complete in the database
     *
     * @param AutomationSchedule $schedule
     * @return AutomationSchedule
     */
    protected function markScheduleAsComplete(AutomationSchedule $schedule): AutomationSchedule
    {
        $schedule->completed_at = now();
        $schedule->save();

        return $schedule;
    }
}
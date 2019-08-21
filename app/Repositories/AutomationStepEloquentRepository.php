<?php

namespace App\Repositories;

use App\Models\AutomationStep;

class AutomationStepEloquentRepository extends BaseEloquentRepository
{
    /**
     * @var string
     */
    protected $modelName = AutomationStep::class;

    /**
     * {@inheritDoc}
     */
    public function update($id, array $data)
    {
        if ($automationStep = parent::update($id, $data))
        {
            $this->afterUpdate($automationStep);
        }

        return $automationStep;
    }

    /**
     * Update any schedules that have not yet been performed
     *
     * @param AutomationStep $automationStep
     * @return mixed
     */
    protected function afterUpdate(AutomationStep $automationStep)
    {
        $sql = "UPDATE automation_schedules
                JOIN subscribers on automation_schedules.subscriber_id = subscribers.id
                SET scheduled_at = subscribers.created_at + INTERVAL ? SECOND
                WHERE scheduled_at >= ?
                    AND started_at IS NULL
                    AND automation_step_id = ?";

        $params = [$automationStep->delay_seconds, now(), $automationStep->id];

        return \DB::update($sql, $params);
    }
}

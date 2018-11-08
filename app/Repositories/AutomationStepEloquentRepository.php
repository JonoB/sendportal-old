<?php

namespace App\Repositories;

use App\Interfaces\AutomationStepRepositoryInterface;
use App\Models\AutomationStep;

class AutomationStepEloquentRepository extends BaseEloquentRepository implements AutomationStepRepositoryInterface
{
    /**
     * @var string
     */
    protected $modelName = AutomationStep::class;

    /**
     * Find the automation step for an automation.
     *
     * @param $automationId
     * @param $automationStepId
     *
     * @return mixed
     */
    public function findStepForAutomation($automationId, $automationStepId)
    {
        return $this->getQueryBuilder()
            ->where('id', $automationStepId)
            ->where('automation_id', $automationId)
            ->firstOrFail();
    }

    /**
     * Update a campaign's email
     *
     * @param int $automationId
     * @param int $automationStepId
     * @param array $data
     *
     * @return AutomationStep $automationStep
     */
    public function updateStepForAutomation(int $automationId, int $automationStepId, array $data) : AutomationStep
    {
        $automationStep = $this->findStepForAutomation($automationId, $automationStepId);

        $automationStep->update($data);

        return $automationStep;
    }
}

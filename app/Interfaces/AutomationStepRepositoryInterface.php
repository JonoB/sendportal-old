<?php

namespace App\Interfaces;

use App\Models\AutomationStep;

interface AutomationStepRepositoryInterface extends BaseEloquentInterface
{
    /**
     * Find the automation step for an automation.
     *
     * @param $automationId
     * @param $automationStepId
     *
     * @return mixed
     */
    public function findStepForAutomation($automationId, $automationStepId);

    /**
     * Update a campaign's email
     *
     * @param int $automationId
     * @param int $automationStepId
     * @param array $data
     *
     * @return AutomationStep $automationStep
     */
    public function updateStepForAutomation(int $automationId, int $automationStepId, array $data): AutomationStep;
}

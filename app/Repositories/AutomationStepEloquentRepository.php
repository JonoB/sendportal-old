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
}

<?php

namespace App\Repositories;

use App\Models\AutomationStep;

class AutomationStepEloquentRepository extends BaseEloquentRepository
{
    /**
     * @var string
     */
    protected $modelName = AutomationStep::class;
}

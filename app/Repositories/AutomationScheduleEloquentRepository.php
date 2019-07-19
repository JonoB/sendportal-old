<?php

namespace App\Repositories;

use App\Models\AutomationSchedule;

class AutomationScheduleEloquentRepository extends BaseEloquentRepository
{
    /**
     * @var string
     */
    protected $modelName = AutomationSchedule::class;
}

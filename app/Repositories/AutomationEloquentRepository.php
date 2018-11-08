<?php

namespace App\Repositories;

use App\Interfaces\AutomationRepositoryInterface;
use App\Models\Automation;
use App\Models\Subscriber;

class AutomationEloquentRepository extends BaseEloquentRepository implements AutomationRepositoryInterface
{
    /**
     * @var string
     */
    protected $modelName = Automation::class;
}

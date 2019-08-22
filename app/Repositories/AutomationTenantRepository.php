<?php

namespace App\Repositories;

use App\Models\Automation;

class AutomationTenantRepository extends BaseTenantRepository
{
    /**
     * @var string
     */
    protected $modelName = Automation::class;
}

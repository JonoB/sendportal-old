<?php

namespace App\Repositories;

use App\Models\Template;

class TemplateTenantRepository extends BaseTenantRepository
{
    protected $modelName = Template::class;
}
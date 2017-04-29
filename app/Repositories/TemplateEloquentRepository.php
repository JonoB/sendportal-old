<?php

namespace App\Repositories;

use App\Interfaces\TemplateRepositoryInterface;
use App\Models\Template;

class TemplateEloquentRepository extends BaseEloquentRepository implements TemplateRepositoryInterface
{
    protected $modelName = Template::class;
}
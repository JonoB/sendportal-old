<?php

namespace App\Repositories;

use App\Interfaces\AutoresponderRepositoryInterface;
use App\Models\Autoresponder;
use App\Models\Subscriber;

class AutoresponderEloquentRepository extends BaseEloquentRepository implements AutoresponderRepositoryInterface
{
    /**
     * @var string
     */
    protected $modelName = Autoresponder::class;
}

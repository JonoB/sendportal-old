<?php

namespace App\Repositories;

use App\Interfaces\SubscriberListRepositoryInterface;
use App\Models\SubscriberList;

class SubscriberListEloquentRepository extends BaseEloquentRepository implements SubscriberListRepositoryInterface
{
    protected $modelName = SubscriberList::class;

}

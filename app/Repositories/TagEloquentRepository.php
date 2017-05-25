<?php

namespace App\Repositories;

use App\Interfaces\TagRepositoryInterface;
use App\Models\Tag;

class TagEloquentRepository extends BaseEloquentRepository implements TagRepositoryInterface
{
    protected $modelName = Tag::class;
}

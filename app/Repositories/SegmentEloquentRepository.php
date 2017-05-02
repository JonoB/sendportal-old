<?php

namespace App\Repositories;

use App\Interfaces\SegmentRepositoryInterface;
use App\Models\Segment;

class SegmentEloquentRepository extends BaseEloquentRepository implements SegmentRepositoryInterface
{
    protected $modelName = Segment::class;
}

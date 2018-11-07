<?php

namespace App\Services\Segments;

use App\Interfaces\SegmentRepositoryInterface;
use App\Models\Segment;

class ApiSegmentService
{
    /**
     * @var SegmentRepositoryInterface
     */
    protected $segments;

    /**
     * @param SegmentRepositoryInterface $segments
     */
    public function __construct(SegmentRepositoryInterface $segments)
    {
        $this->segments = $segments;
    }

    /**
     * Store a new segment, optionally including attaching subscribers
     *
     * @param array $data
     *
     * @return Segment
     */
    public function store(array $data): Segment
    {
        $segment = $this->segments->store(array_except($data, 'subscribers'));

        if ( ! empty($data['subscribers']))
        {
            $segment->subscribers()->attach($data['subscribers']);
        }

        return $segment;
    }
}

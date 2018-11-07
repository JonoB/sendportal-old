<?php

namespace App\Services\Segments;

use App\Interfaces\SegmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ApiSegmentSubscriberService
{
    /**
     * @var SegmentRepositoryInterface
     */
    protected $segments;

    /**
     * ApiSegmentSubscriberService constructor.
     *
     * @param SegmentRepositoryInterface $segments
     */
    public function __construct(SegmentRepositoryInterface $segments)
    {
        $this->segments = $segments;
    }

    /**
     * Add new subscribers to a segment
     *
     * @param string $segmentId
     * @param array $subscriberIds
     *
     * @return Collection
     */
    public function store(string $segmentId, array $subscriberIds): Collection
    {
        $segment = $this->segments->find($segmentId);

        $segment->subscribers()->attach($subscriberIds);

        return $segment->subscribers;
    }

    /**
     * Sync subscribers on a segment
     *
     * @param string $segmentId
     * @param array $subscriberIds
     *
     * @return Collection
     */
    public function update(string $segmentId, array $subscriberIds): Collection
    {
        $segment = $this->segments->find($segmentId);

        $segment->subscribers()->sync($subscriberIds);

        return $segment->subscribers;
    }

    /**
     * Remove subscribers from a segment
     *
     * @param string $segmentId
     * @param array $subscriberIds
     *
     * @return Collection
     */
    public function destroy(string $segmentId, array $subscriberIds): Collection
    {
        $segment = $this->segments->find($segmentId);

        $segment->subscribers()->detach($subscriberIds);

        return $segment->subscribers;
    }
}

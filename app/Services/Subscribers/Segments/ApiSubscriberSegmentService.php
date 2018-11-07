<?php

namespace App\Services\Subscribers\Segments;

use App\Interfaces\SubscriberRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ApiSubscriberSegmentService
{
    /**
     * @var SubscriberRepositoryInterface
     */
    protected $subscribers;

    /**
     * @param SubscriberRepositoryInterface $subscribers
     */
    public function __construct(SubscriberRepositoryInterface $subscribers)
    {
        $this->subscribers = $subscribers;
    }

    /**
     * Add segments to a subscriber
     *
     * @param string $subscriberId
     * @param array $segmentIds
     *
     * @return Collection
     */
    public function store(string $subscriberId, array $segmentIds): Collection
    {
        $subscriber = $this->subscribers->find($subscriberId);

        $subscriber->segments()->attach($segmentIds);

        return $subscriber->segments;
    }

    /**
     * Sync the list of segments a subscriber is associated with
     *
     * @param string $subscriberId
     * @param array $segmentIds
     *
     * @return Collection
     */
    public function update(string $subscriberId, array $segmentIds): Collection
    {
        $subscriber = $this->subscribers->find($subscriberId, ['segments']);

        $subscriber->segments()->sync($segmentIds);

        return $subscriber->segments;
    }

    /**
     * Remove segments from a subscriber
     *
     * @param string $subscriberId
     * @param array $segmentIds
     *
     * @return Collection
     */
    public function destroy(string $subscriberId, array $segmentIds): Collection
    {
        $subscriber = $this->subscribers->find($subscriberId);

        $subscriber->segments()->detach($segmentIds);

        return $subscriber->segments;
    }
}

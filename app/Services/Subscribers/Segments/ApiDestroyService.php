<?php

namespace App\Services\Subscribers\Segments;

use App\Interfaces\SubscriberRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ApiDestroyService
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
     * Remove segments from a subscriber
     *
     * @param string $subscriberId
     * @param array $segmentIds
     *
     * @return Collection
     */
    public function __invoke(string $subscriberId, array $segmentIds): Collection
    {
        $subscriber = $this->subscribers->find($subscriberId);

        $subscriber->segments()->detach($segmentIds);

        return $subscriber->segments;
    }
}

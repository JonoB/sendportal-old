<?php

namespace App\Services\Subscribers\Segments;

use App\Interfaces\SubscriberRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ApiUpdateService
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
     * Sync the list of segments a subscriber is associated with
     *
     * @param string $subscriberId
     * @param array $segmentIds
     *
     * @return Collection
     */
    public function __invoke(string $subscriberId, array $segmentIds): Collection
    {
        $subscriber = $this->subscribers->find($subscriberId, ['segments']);

        $subscriber->segments()->sync($segmentIds);

        return $subscriber->segments;
    }
}

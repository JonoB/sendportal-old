<?php

namespace App\Services\Subscribers;

use App\Events\SubscriberAddedEvent;
use App\Models\Subscriber;
use App\Repositories\SubscriberTenantRepository;

class ApiSubscriberService
{
    /**
     * @var SubscriberTenantRepository
     */
    protected $subscribers;

    /**
     * @param SubscriberTenantRepository $subscribers
     */
    public function __construct(SubscriberTenantRepository $subscribers)
    {
        $this->subscribers = $subscribers;
    }

    /**
     * Create or update a subscriber
     *
     * @param int $teamId
     * @param array $data
     * @return Subscriber
     * @throws \Exception
     */
    public function store($teamId, array $data): Subscriber
    {
        if (array_get($data, 'id') !== null)
        {
            return $this->subscribers->update($teamId, $data['id'], array_except($data, ['id', 'segments']));
        }

        $subscriber = $this->subscribers->findBy($teamId, 'email', array_get($data, 'email'));

        if ($subscriber)
        {
            return $this->subscribers->update($teamId, $subscriber->id, array_except($data, 'segments'));
        }

        $subscriber = $this->subscribers->store($teamId, array_except($data, ['segments']));

        event(new SubscriberAddedEvent($subscriber));

        $this->handleSegments($data, $subscriber);

        return $subscriber;
    }

    /**
     * Handle attaching segments to a subscriber
     *
     * @param array $data
     * @param Subscriber $subscriber
     * @return Subscriber
     */
    protected function handleSegments(array $data, Subscriber $subscriber): Subscriber
    {
        if ( ! empty($data['segments']))
        {
            $subscriber->segments()->attach($data['segments']); // @todo JB I think that this should be sync instead of attach?
        }

        return $subscriber;
    }
}

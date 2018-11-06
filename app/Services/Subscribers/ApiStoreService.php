<?php

namespace App\Services\Subscribers;

use App\Interfaces\SubscriberRepositoryInterface;
use App\Models\Subscriber;

class ApiStoreService
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
     * Create or update a subscriber
     *
     * @param array $data
     *
     * @return Subscriber
     */
    public function createOrUpdate(array $data): Subscriber
    {
        if (array_get($data, 'id') !== null)
        {
            return $this->subscribers->update($data['id'], array_except($data, 'id'));
        }

        $subscriber = $this->subscribers->findBy('email', array_get($data, 'email'));

        if ($subscriber)
        {
            return $this->subscribers->update($subscriber->id, $data);
        }

        $subscriber = $this->subscribers->store(array_except($data, ['segments']));

        $this->handleSegments($data, $subscriber);

        return $subscriber;
    }

    /**
     * Handle attaching segments to a subscriber
     *
     * @param array $data
     * @param Subscriber $subscriber
     *
     * @return Subscriber
     */
    protected function handleSegments(array $data, Subscriber $subscriber): Subscriber
    {
        if (isset($data['segments']))
        {
            $subscriber->segments()->attach($data['segments']);
        }

        return $subscriber;
    }
}

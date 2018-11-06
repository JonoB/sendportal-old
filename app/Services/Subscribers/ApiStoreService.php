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

        return $this->subscribers->store($data);
    }
}

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
    public function __invoke(array $data): Subscriber
    {
        if (array_get($data, 'id') !== null)
        {
            return $this->subscribers->update($data['id'], array_except($data, ['id', 'segments']));
        }

        $subscriber = $this->subscribers->findBy('email', array_get($data, 'email'));

        if ($subscriber)
        {
            return $this->subscribers->update($subscriber->id, array_except($data, 'segments'));
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
        if ( ! empty($data['segments']))
        {
            $subscriber->segments()->attach($data['segments']);
        }

        return $subscriber;
    }
}

<?php

namespace App\Services\Subscribers;

use App\Interfaces\SubscriberRepositoryInterface;
use App\Models\Subscriber;

class ImportSubscriberService
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
    public function import(array $data): Subscriber
    {
        $subscriber = null;

        if (array_get($data, 'id') !== null)
        {
            $subscriber = $this->subscribers->findBy('id', $data['id'], ['segments']);
        }

        if (! $subscriber)
        {
            $subscriber = $this->subscribers->findBy('email', array_get($data, 'email'), ['segments']);
        }

        if (! $subscriber)
        {
            $subscriber = $this->subscribers->store(array_except($data, ['id', 'segments']));
        }

        $this->subscribers->update($subscriber->id, array_except($data, 'segments'));

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
            $subscriber->segments()->attach(array_merge($subscriber->segments->pluck('id')->toArray(), $data['segments']));
        }

        return $subscriber;
    }
}

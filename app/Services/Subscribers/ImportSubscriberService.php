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

        if ( ! $subscriber)
        {
            $subscriber = $this->subscribers->findBy('email', array_get($data, 'email'), ['segments']);
        }

        if ( ! $subscriber)
        {
            $subscriber = $this->storeSubscriber($data);
        }

        $data['segments'] = array_merge($subscriber->segments->pluck('id')->toArray(), array_get($data, 'segments'));

        $this->subscribers->update($subscriber->id, $data);

        return $subscriber;
    }

    protected function storeSubscriber($data)
    {
        $subscriber = new Subscriber();
        $subscriber->fill(array_except($data, ['id', 'segments']));

        return $subscriber->save();
    }

}

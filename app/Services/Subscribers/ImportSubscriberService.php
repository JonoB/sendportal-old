<?php

namespace App\Services\Subscribers;

use App\Repositories\SubscriberTenantRepository;
use App\Models\Subscriber;

class ImportSubscriberService
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

        $data['segments'] = array_merge($subscriber->segments->pluck('id')->toArray(), array_get($data, 'segments'));

        $this->subscribers->update($subscriber->id, $data);

        return $subscriber;
    }
}

<?php

namespace App\Interfaces;

use App\Models\Subscriber;

interface SubscriberRepositoryInterface extends BaseEloquentInterface
{
    /**
     * Sync tags to a contact
     *
     * @param Subscriber $subscriber
     * @param array $tags
     * @return mixed
     */
    public function syncTags(Subscriber $subscriber, array $tags = []);
}

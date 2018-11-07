<?php

namespace App\Interfaces;

use App\Models\Subscriber;

interface SubscriberRepositoryInterface extends BaseEloquentInterface
{
    /**
     * Get all Subscribers eligible for exporting.
     *
     * @param  array  $fields
     * @param  string $orderBy
     * @return Collection
     */
    public function export(array $fields, $orderBy = 'id');

    /**
     * Sync tags to a contact
     *
     * @param Subscriber $subscriber
     * @param array $tags
     * @return mixed
     */
    public function syncTags(Subscriber $subscriber, array $tags = []);
}

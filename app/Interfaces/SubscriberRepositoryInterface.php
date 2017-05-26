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

    /**
     * Paginate list of subscribers for the given subscriber list
     *
     * @param string $listId
     * @param string $orderBy
     * @param array $relations
     * @param int $paginate
     * @param array $parameters
     *
     * @return mixed
     */
    public function paginateListSubscribers($listId, $orderBy = 'name', array $relations = [], $paginate = 50, array $parameters = []);
}

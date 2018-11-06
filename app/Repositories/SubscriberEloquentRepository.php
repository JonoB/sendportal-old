<?php

namespace App\Repositories;

use App\Interfaces\SubscriberRepositoryInterface;
use App\Models\Subscriber;

class SubscriberEloquentRepository extends BaseEloquentRepository implements SubscriberRepositoryInterface
{
    /**
     * @var string
     */
    protected $modelName = Subscriber::class;

    /**
     * Sync tags to a subscriber
     *
     * @param Subscriber $subscriber
     * @param array $tags
     * @return mixed
     */
    public function syncTags(Subscriber $subscriber, array $tags = [])
    {
        return $subscriber->tags()->sync($tags);
    }

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
    public function paginateListSubscribers($listId, $orderBy = 'name', array $relations = [], $paginate = 50, array $parameters = [])
    {
        $instance = $this->getQueryBuilder();

        $this->parseOrder($orderBy);

        // $parameters can be extended in child classes for filtering
        $parameters = [];

        return $instance->with($relations)
            ->where('subscriber_list_id', '=', $listId)
            ->orderBy($this->getOrderBy(), $this->getOrderDirection())
            ->paginate($paginate);
    }
}

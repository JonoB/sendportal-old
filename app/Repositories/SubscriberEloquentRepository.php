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
     * Get all Subscribers eligible for exporting.
     *
     * @param  array  $fields
     * @param  string $orderBy
     * @return Collection
     */
    public function export(array $fields, $orderBy = 'id')
    {
        $instance = $this->getQueryBuilder();

        $this->parseOrder($orderBy);

        return $instance
            ->orderBy($this->getOrderBy(), $this->getOrderDirection())
            ->get($fields);
    }

    /**
     * Sync Segments to a Subscriber.
     *
     * @param  Subscriber $subscriber
     * @param  array      $segments
     * @return mixed
     */
    public function syncSegments(Subscriber $subscriber, array $segments = [])
    {
        return $subscriber->segments()->sync($segments);
    }

    /**
     * Update the Subscriber
     *
     * @param int $id The model id
     * @param array $data The input data
     * @return object model instance
     */
    public function update($id, array $data)
    {
        $this->instance = $this->find($id);

        $this->executeUpdate($id, array_except($data, ['segments']));

        $this->syncSegments($this->instance, array_get($data, 'segments', []));

        return $this->instance;
    }
}

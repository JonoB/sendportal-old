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
     * Apply parameters, which can be extended in child classes for filtering
     *
     * @param $query
     * @param array $filters
     * @return mixed
     */
    protected function applyFilters($instance, array $filters = [])
    {
        $instance->select('subscribers.*');

        if ($segmentId = array_get($filters, 'segment_id'))
        {
             $instance->leftJoin('segment_subscriber', 'subscribers.id', '=', 'segment_subscriber.subscriber_id')
              ->whereIn('segment_subscriber.segment_id', $segmentId);
        }

        if ($name = array_get($filters, 'name'))
        {
            $name = '%' . $name . '%';

             $instance->where(function($instance) use ($name) {
                 $instance->where('subscribers.first_name', 'like', $name)
                     ->orWhere('subscribers.last_name', 'like', $name)
                     ->orWhere('subscribers.email', 'like', $name);
             });

        }

        return $instance;
    }


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

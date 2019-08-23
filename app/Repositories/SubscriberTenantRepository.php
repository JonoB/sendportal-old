<?php

namespace App\Repositories;

use App\Models\Subscriber;

class SubscriberTenantRepository extends BaseTenantRepository
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
     * Sync Segments to a Subscriber.
     *
     * @param Subscriber $subscriber
     * @param array $segments
     * @return mixed
     */
    public function syncSegments(Subscriber $subscriber, array $segments = [])
    {
        return $subscriber->segments()->sync($segments);
    }

    /**
     * {@inheritDoc}
     */
    public function store($teamId, array $data)
    {
        $this->checkTenantData($data);

        $instance = $this->getNewInstance();

        $subscriber = $this->executeSave($teamId, array_except($data, ['segments']), $instance);

        $this->syncSegments($instance, array_get($data, 'segments', []));

        return $subscriber;
    }

    /**
     * {@inheritDoc}
     */
    public function update($teamId, $id, array $data)
    {
        $this->checkTenantData($data);

        $instance = $this->find($teamId, $id);

        $subscriber = $this->executeSave($teamId, array_except($data, ['segments']), $instance);

        $this->syncSegments($this->instance, array_get($data, 'segments', []));

        return $subscriber;
    }
}

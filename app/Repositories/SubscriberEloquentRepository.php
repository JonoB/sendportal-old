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

        $this->executeUpdate($id, array_except($data, ['tags', 'segments']));

        $this->syncTags($this->instance, array_get($data, 'tags', []));

        $this->syncSegments($this->instance, array_get($data, 'segments', []));

        return $this->instance;
    }
}

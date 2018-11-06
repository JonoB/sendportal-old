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
     * Update the Subscriber
     *
     * @param int $id The model id
     * @param array $data The input data
     * @return object model instance
     */
    public function update($id, array $data)
    {
        $this->instance = $this->find($id);

        $this->executeUpdate($id, $data);

        $this->syncTags($this->instance, array_get($data, 'tags', []));

        return $this->instance;
    }
}

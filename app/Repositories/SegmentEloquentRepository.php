<?php

namespace App\Repositories;

use App\Interfaces\SegmentRepositoryInterface;
use App\Models\Segment;

class SegmentEloquentRepository extends BaseEloquentRepository implements SegmentRepositoryInterface
{
    protected $modelName = Segment::class;

    /**
     * Update the model instance
     *
     * @param int $id
     * @param array $data
     *
     * @return Segment
     */
    public function update($id, array $data)
    {
        $this->instance = $this->find($id);

        $this->executeUpdate($id, $data);

        $this->syncSubscribers($this->instance, array_get($data, 'subscribers', []));

        return $this->instance;
    }

    /**
     * Syn subscribers
     *
     * @param Segment $segment
     * @param array $subscribers
     *
     * @return bool
     */
    public function syncSubscribers(Segment $segment, array $subscribers = [])
    {
        return $segment->subscribers()->sync($subscribers);
    }
}

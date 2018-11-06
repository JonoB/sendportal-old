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
     * @param  int    $id
     * @param  array  $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $this->instance = $this->find($id);

        $this->executeUpdate($id, $data);

        return $this->syncSubscribers($this->instance, array_get($data, 'subscribers', []));
    }

    /**
     * Syn subscribers
     *
     * @param  Segment $segment
     * @param  array   $subscribers
     * @return bool
     */
    public function syncSubscribers(Segment $segment, array $subscribers = [])
    {
        return $segment->subscribers()->sync($subscribers);
    }
}

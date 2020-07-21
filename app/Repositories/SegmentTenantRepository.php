<?php

namespace App\Repositories;

use App\Models\Segment;

class SegmentTenantRepository extends BaseTenantRepository
{
    protected $modelName = Segment::class;

    /**
     * {@inheritDoc}
     */
    public function update($teamId, $id, array $data)
    {
        $this->instance = $this->find($id);

        $this->executeUpdate($id, $data);

        $this->syncSubscribers($this->instance, array_get($data, 'subscribers', []));

        return $this->instance;
    }

    /**
     * Sync subscribers
     *
     * @param Segment $segment
     * @param array $subscribers
     * @return array
     */
    public function syncSubscribers(Segment $segment, array $subscribers = [])
    {
        return $segment->subscribers()->sync($subscribers);
    }
}

<?php

namespace App\Interfaces;

use App\Models\Subscriber;

interface SubscriberRepositoryInterface extends BaseEloquentInterface
{
    /**
     * Get all Subscribers eligible for exporting.
     *
     * @param  array  $fields
     * @param  string $orderBy
     * @return Collection
     */
    public function export(array $fields, $orderBy = 'id');
}

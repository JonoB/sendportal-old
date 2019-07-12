<?php

namespace App\Repositories;

use App\Models\Delivery;

class DeliveryEloquentRepository extends BaseEloquentRepository
{
    /**
     * @var string
     */
    protected $modelName = Delivery::class;

    protected function applyFilters($instance, array $filters = [])
    {
        return $this->applySentFilter($instance, $filters);
    }

    protected function applySentFilter($instance, $filters = [])
    {
        if ($sentAt = array_get($filters, 'draft'))
        {
            $instance->whereNull('sent_at');
        }

        else if ($sentAt = array_get($filters, 'sent'))
        {
            $instance->whereNotNull('sent_at');
        }

        return $instance;
    }
}

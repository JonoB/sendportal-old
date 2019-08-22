<?php

namespace App\Repositories;

use App\Models\Message;

class MessageTenantRepository extends BaseTenantRepository
{
    protected $modelName = Message::class;

    /**
     * {@inheritDoc}
     */
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

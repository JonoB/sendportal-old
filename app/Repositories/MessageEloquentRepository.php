<?php

namespace App\Repositories;

use App\Models\Message;

class MessageEloquentRepository extends BaseEloquentRepository
{
    /**
     * @var string
     */
    protected $modelName = Message::class;

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

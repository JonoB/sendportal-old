<?php

namespace App\Services\Deliveries;

use App\Models\Delivery;

class MarkDeliverySent
{
    /**
     * Mark the delivery as sent in the database
     *
     * @param Delivery $delivery
     */
    public function handle(Delivery $delivery)
    {
        $this->markDeliveryAsSent($delivery);

        //return $next($schedule);
    }

    /**
     * Execute the database query
     *
     * @param Delivery $delivery
     */
    protected function markDeliveryAsSent(Delivery $delivery): void
    {
        $delivery->sent_at = now();
        $delivery->save();
    }
}
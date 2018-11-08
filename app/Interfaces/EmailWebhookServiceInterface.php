<?php

namespace App\Interfaces;

use Carbon\Carbon;

interface EmailWebhookServiceInterface
{

    /**
     * @param $messageId
     * @param $link
     * @return mixed
     */
    public function handleClick($messageId, $link);

    /**
     * @param $messageId
     * @param Carbon $timestamp
     * @param $ipAddress
     */
    public function handleOpen($messageId, Carbon $timestamp, $ipAddress);

    /**
     * @param $messageId
     * @param Carbon $timestamp
     */
    public function handleDelivery($messageId, Carbon $timestamp);

    /**
     * @param $messageId
     */
    public function handleComplaint($messageId);

    /**
     * @param $messageId
     */
    public function handlePermanentBounce($messageId);
}

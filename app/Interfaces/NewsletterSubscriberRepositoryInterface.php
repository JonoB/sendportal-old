<?php

namespace App\Interfaces;

interface NewsletterSubscriberRepositoryInterface extends BaseEloquentInterface
{
    /**
     * Track opens
     *
     * @param string $newsletterId
     * @param string $subscriberId
     * @param string $ipAddress
     * @return mixed
     */
    public function incrementOpenCount($newsletterId, $subscriberId, $ipAddress);

    /**
     * Track clicks
     *
     * @param string $newsletterId
     * @param string $subscriberId
     * @param string $ipAddress
     * @return mixed
     */
    public function incrementClickCount($newsletterId, $subscriberId);

    /**
     * Return the unique open count per hour
     *
     * @param int $newsletterId
     * @return array
     */
    public function countUniqueOpensPerHour($newsletterId);
}

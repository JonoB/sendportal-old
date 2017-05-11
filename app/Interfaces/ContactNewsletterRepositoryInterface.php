<?php

namespace App\Interfaces;

interface ContactNewsletterRepositoryInterface extends BaseEloquentInterface
{
    /**
     * Track opens
     *
     * @param string $newsletterId
     * @param string $contactId
     * @param string $ipAddress
     * @return mixed
     */
    public function incrementOpenCount($newsletterId, $contactId, $ipAddress);

    /**
     * Track clicks
     *
     * @param string $newsletterId
     * @param string $contactId
     * @param string $ipAddress
     * @return mixed
     */
    public function incrementClickCount($newsletterId, $contactId);

    /**
     * Return the unique open count per hour
     *
     * @param int $newsletterId
     * @return array
     */
    public function countUniqueOpensPerHour($newsletterId);
}

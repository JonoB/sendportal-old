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
     * Return the open count for a newsletter
     *
     * @param int $newsletterId
     * @return int
     */
    public function getUniqueOpenCount($newsletterId);
}

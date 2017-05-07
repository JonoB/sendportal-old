<?php

namespace App\Interfaces;

interface NewsletterOpenRepositoryInterface extends BaseEloquentInterface
{

    /**
     * Track an open record
     *
     * @param int $newsletterId
     * @param int $contactId
     * @param string $ipAddress
     * @return mixed
     */
    public function storeOpenTrack($newsletterId, $contactId, $ipAddress);

    /**
     * Return the open count for a newsletter
     *
     * @param int $newsletterId
     * @return int
     */
    public function getUniqueOpenCount($newsletterId);
}

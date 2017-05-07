<?php

namespace App\Interfaces;

interface NewsletterUrlsRepositoryInterface extends BaseEloquentInterface
{
    /**
     * Track an open record
     *
     * @param int $newsletterId
     * @param int $contactId
     * @param string $ipAddress
     * @return mixed
     */
    public function storeClickTrack($newsletterId, $urlId);

    /**
     * Return the click count for a single link
     *
     * @param int $newsletterId
     * @param int $urlId
     * @return int
     */
    public function getUrlClickCount($newsletterId, $urlId);

    /**
     * Return the total click count for a newsletter
     *
     * @param int $newsletterId
     * @return int
     */
    public function getTotalClickCount($newsletterId);
}

<?php

namespace App\Interfaces;

interface NewsletterUrlsRepositoryInterface extends BaseEloquentInterface
{
    /**
     * Track an open record
     *
     * @param string $urlId
     * @param string $ipAddress
     * @return mixed
     */
    public function storeClickTrack($urlId);

    /**
     * Return the click count for a single link
     *
     * @param string $urlId
     * @return int
     */
    public function getUrlClickCount($urlId);

    /**
     * Return the total click count for a newsletter
     *
     * @param int $newsletterId
     * @return int
     */
    public function getTotalClickCount($newsletterId);
}

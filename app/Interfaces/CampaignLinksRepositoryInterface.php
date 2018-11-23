<?php

namespace App\Interfaces;

interface CampaignLinksRepositoryInterface extends BaseEloquentInterface
{
    /**
     * Track an open record
     *
     * @param string $urlId
     * @param string $ipAddress
     * @return mixed
     */
    public function incrementClickCount($urlId);

    /**
     * Return the click count for a single link
     *
     * @param string $urlId
     * @return int
     */
    public function getLinkClickCount($urlId);

    /**
     * Return the total click count for a campaign
     *
     * @param int $campaignId
     * @return int
     */
    public function getTotalClickCount($campaignId);
}

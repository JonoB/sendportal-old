<?php

namespace App\Interfaces;

interface CampaignSubscriberRepositoryInterface extends BaseEloquentInterface
{
    /**
     * Track opens
     *
     * @param int $campaignId
     * @param int $subscriberId
     * @param string $ipAddress
     * @return mixed
     */
    public function incrementOpenCount(int $campaignId, int $subscriberId, $ipAddress);

    /**
     * Track clicks
     *
     * @param int $campaignId
     * @param int $subscriberId
     * @param string $ipAddress
     * @return mixed
     */
    public function incrementClickCount(int $campaignId, int $subscriberId);

    /**
     * Return the unique open count per hour
     *
     * @param int $campaignId
     * @return array
     */
    public function countUniqueOpensPerHour(int $campaignId);
}

<?php

namespace App\Interfaces;

interface CampaignSubscriberTenantRepository extends BaseEloquentInterface
{
    /**
     * Track opens
     *
     * @param string $campaignId
     * @param string $subscriberId
     * @param string $ipAddress
     * @return mixed
     */
    public function incrementOpenCount($campaignId, $subscriberId, $ipAddress);

    /**
     * Track clicks
     *
     * @param string $campaignId
     * @param string $subscriberId
     * @param string $ipAddress
     * @return mixed
     */
    public function incrementClickCount($campaignId, $subscriberId);

    /**
     * Return the unique open count per hour
     *
     * @param int $campaignId
     * @return array
     */
    public function countUniqueOpensPerHour($campaignId);
}

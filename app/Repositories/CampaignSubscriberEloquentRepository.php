<?php

namespace App\Repositories;

use App\Interfaces\CampaignSubscriberRepositoryInterface;
use App\Models\CampaignSubscriber;

class CampaignSubscriberEloquentRepository extends BaseEloquentRepository implements CampaignSubscriberRepositoryInterface
{
    protected $modelName = CampaignSubscriber::class;

    /**
     * Track opens
     *
     * @param string $campaignId
     * @param string $subscriberId
     * @param string $ipAddress
     * @return mixed
     */
    public function incrementOpenCount(int $campaignId, int $subscriberId, $ipAddress)
    {
        return $this->getNewInstance()
            ->where('campaign_id', $campaignId)
            ->where('subscriber_id', $subscriberId)
            ->update([
                'open_count' => \DB::raw('open_count + 1'),
                'ip' => $ipAddress,
            ]);
    }

    /**
     * Track clicks
     *
     * @param int $campaignId
     * @param int $subscriberId
     * @return mixed
     */
    public function incrementClickCount(int $campaignId, int $subscriberId)
    {
        return $this->getNewInstance()
            ->where('campaign_id', $campaignId)
            ->where('subscriber_id', $subscriberId)
            ->increment('click_count');
    }

    /**
     * Return the open count for a campaign
     *
     * @param int $campaignId
     * @return int
     */
    public function getUniqueOpenCount(int $campaignId)
    {
        return $this->getNewInstance()
            ->where('campaign_id', $campaignId)
            ->whereNotNull('opened_at')
            ->count();
    }

    /**
     * Count the number of unique opens per hour.
     *
     * @param int $campaignId
     *
     * @return array
     */
    public function countUniqueOpensPerHour(int $campaignId)
    {
        return $this->getNewInstance()
            ->select(\DB::raw('COUNT(open_count) as open_count, DATE_FORMAT(opened_at, "%d-%b %k:00") as opened_at'))
            ->where('campaign_id', $campaignId)
            ->whereNotNull('opened_at')
            ->groupBy(\DB::raw('HOUR(opened_at)'))
            ->orderBy('opened_at')
            ->get();
    }
}

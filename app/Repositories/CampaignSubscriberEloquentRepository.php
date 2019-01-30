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
    public function incrementOpenCount($campaignId, $subscriberId, $ipAddress)
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
     * @param string $campaignId
     * @param string $subscriberId
     * @return mixed
     */
    public function incrementClickCount($campaignId, $subscriberId)
    {
        return $this->getQueryBuilder()
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
    public function getUniqueOpenCount($campaignId)
    {
        return $this->getQueryBuilder()
            ->where('campaign_id', $campaignId)
            ->where('open_count', '>', 0)
            ->count();
    }

    public function countUniqueOpensPerHour($campaignId)
    {
        return $this->getQueryBuilder()
            ->select(\DB::raw('SUM(open_count) as open_count, DATE_FORMAT(opened_at, "%d-%b %k:00") as opened_at'))
            ->where('campaign_id', $campaignId)
            ->where('open_count', '>', 0)
            ->groupBy(\DB::raw('HOUR(opened_at), DAY(opened_at)'))
            ->orderBy('opened_at')
            ->get();
    }
}

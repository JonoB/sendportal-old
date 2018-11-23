<?php

namespace App\Repositories;

use App\Interfaces\CampaignLinksRepositoryInterface;
use App\Models\CampaignLink;

class CampaignLinksRepository extends BaseEloquentRepository implements CampaignLinksRepositoryInterface
{
    protected $modelName = CampaignLink::class;

    public function getBy($field, $value, array $relations = [])
    {
        return $this->getQueryBuilder()
            ->with($relations)
            ->where($field, $value)
            ->orderBy('click_count', 'desc')
            ->get();
    }

    /**
     * Track an open record
     *
     * @param string $urlId
     * @return mixed
     */
    public function incrementClickCount($urlId)
    {
        return $this->getNewInstance()
            ->where('id', $urlId)
            ->increment('click_count');
    }

    /**
     * Return the click count for a single link
     *
     * @param string $urlId
     * @return int
     */
    public function getLinkClickCount($urlId)
    {
        return $this->getNewInstance()
            ->where('id', $urlId)
            ->sum('click_count');
    }

    /**
     * Return the total click count for a campaign
     *
     * @param int $campaignId
     * @return int
     */
    public function getTotalClickCount($campaignId)
    {
        return $this->getNewInstance()
            ->where('campaign_id', $campaignId)
            ->sum('click_count');
    }
}

<?php

namespace App\Repositories;

use App\Interfaces\CampaignUrlsRepositoryInterface;
use App\Models\CampaignUrl;

class CampaignUrlsEloquentRepository extends BaseEloquentRepository implements CampaignUrlsRepositoryInterface
{
    protected $modelName = CampaignUrl::class;

    public function getBy($field, $value, array $relations = [])
    {
        return $this->getQueryBuilder()
            ->with($relations)
            ->where($field, $value)
            ->orderBy('counter', 'desc')
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
            ->increment('counter');
    }

    /**
     * Return the click count for a single link
     *
     * @param string $urlId
     * @return int
     */
    public function getUrlClickCount($urlId)
    {
        return $this->getNewInstance()
            ->where('id', $urlId)
            ->sum('counter');
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
            ->sum('counter');
    }
}

<?php

namespace App\Services;

use App\Interfaces\CampaignSubscriberRepositoryInterface;
use App\Interfaces\CampaignReportServiceInterface;
use App\Interfaces\CampaignLinksRepositoryInterface;

class CampaignReportService implements CampaignReportServiceInterface
{
    /**
     * @var CampaignSubscriberRepositoryInterface
     */
    protected $campaignSubscriberRepository;

    /**
     * @var CampaignLinksRepositoryInterface
     */
    protected $campaignUrlsRepository;

    /**
     * CampaignReportService constructor.
     *
     * @param CampaignSubscriberRepositoryInterface $campaignSubscriberRepository
     * @param CampaignLinksRepositoryInterface $campaignUrlsRepository
     */
    public function __construct(
        CampaignSubscriberRepositoryInterface $campaignSubscriberRepository,
        CampaignLinksRepositoryInterface $campaignUrlsRepository
    )
    {
        $this->campaignSubscriberRepository = $campaignSubscriberRepository;
        $this->campaignUrlsRepository = $campaignUrlsRepository;
    }

    public function opensPerHour($campaignId)
    {
        $opensPerHour = $this->campaignSubscriberRepository->countUniqueOpensPerHour($campaignId);

        $chartLabels = [];
        $chartData = [];
        foreach ($opensPerHour as $item)
        {
            $chartLabels[] = $item->opened_at;
            $chartData[] = $item->open_count;
        }

        return [
            'labels' => json_encode($chartLabels),
            'data' => json_encode($chartData),
        ];
    }

    public function campaignUrls($campaignId)
    {
        return $this->campaignUrlsRepository->getBy('campaign_id', $campaignId);
    }


}

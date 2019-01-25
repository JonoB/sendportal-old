<?php

namespace App\Services;

use App\Interfaces\CampaignLinksRepositoryInterface;
use App\Interfaces\CampaignSubscriberRepositoryInterface;
use App\Interfaces\CampaignReportServiceInterface;

class CampaignReportService implements CampaignReportServiceInterface
{
    /**
     * @var CampaignSubscriberRepositoryInterface
     */
    protected $campaignSubscriberRepository;

    /**
     * @var CampaignLinksRepositoryInterface
     */
    private $campaignLinksRepository;

    /**
     * CampaignReportService constructor.
     *
     * @param CampaignSubscriberRepositoryInterface $campaignSubscriberRepository
     * @param CampaignLinksRepositoryInterface $campaignLinksRepository
     */
    public function __construct(
        CampaignSubscriberRepositoryInterface $campaignSubscriberRepository,
        CampaignLinksRepositoryInterface $campaignLinksRepository
    )
    {
        $this->campaignSubscriberRepository = $campaignSubscriberRepository;
        $this->campaignLinksRepository = $campaignLinksRepository;
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

    public function campaignLinks($campaignId)
    {
        return $this->campaignLinksRepository->getBy('campaign_id', $campaignId);
    }


}

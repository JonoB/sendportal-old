<?php

namespace App\Services;

use App\Interfaces\CampaignLinksRepositoryInterface;
use App\Interfaces\CampaignSubscriberTenantRepository;
use App\Interfaces\CampaignReportServiceInterface;

class CampaignReportService implements CampaignReportServiceInterface
{
    /**
     * @var CampaignSubscriberTenantRepository
     */
    protected $campaignSubscriberRepository;

    /**
     * @var CampaignLinksRepositoryInterface
     */
    private $campaignLinksRepository;

    /**
     * CampaignReportService constructor.
     *
     * @param CampaignSubscriberTenantRepository $campaignSubscriberRepository
     * @param CampaignLinksRepositoryInterface $campaignLinksRepository
     */
    public function __construct(
        CampaignSubscriberTenantRepository $campaignSubscriberRepository,
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

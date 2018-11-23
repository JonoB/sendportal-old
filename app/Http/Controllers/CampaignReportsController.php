<?php

namespace App\Http\Controllers;

use App\Interfaces\CampaignReportServiceInterface;
use App\Interfaces\CampaignRepositoryInterface;
use App\Models\CampaignStatus;
use App\Repositories\CampaignSubscriberEloquentRepository;
use App\Services\CampaignReportService;
use Illuminate\Http\RedirectResponse;

class CampaignReportsController extends Controller
{
    /**
     * @var CampaignRepositoryInterface
     */
    protected $campaignRepo;

    /**
     * @var CampaignReportService
     */
    protected $campaignReportService;

    /**
     * @var CampaignSubscriberEloquentRepository
     */
    private $campaignSubscribers;

    /**
     * CampaignsController constructor.
     *
     * @param CampaignRepositoryInterface $campaignRepository #
     * @param CampaignReportServiceInterface $campaignReportService
     * @param CampaignSubscriberEloquentRepository $campaignSubscribers
     */
    public function __construct(
        CampaignRepositoryInterface $campaignRepository,
        CampaignReportServiceInterface $campaignReportService,
        CampaignSubscriberEloquentRepository $campaignSubscribers
    )
    {
        $this->campaignRepo = $campaignRepository;
        $this->campaignReportService = $campaignReportService;
        $this->campaignSubscribers = $campaignSubscribers;
    }

    /**
     * Show a campaign's report.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\View\View
     */
    public function show(int $id)
    {
        $campaign = $this->campaignRepo->find($id);

        if ($campaign->status_id == CampaignStatus::STATUS_DRAFT)
        {
            return redirect()->route('campaigns.edit', $id);
        }

        if ($campaign->status_id != CampaignStatus::STATUS_SENT)
        {
            return redirect()->route('campaigns.status', $id);
        }

        $chartData = $this->campaignReportService->opensPerHour($id);
        $campaignLinks = $this->campaignReportService->campaignUrls($id);
        $uniqueOpenCount = $this->campaignSubscribers->getUniqueOpenCount($id);
        return view('campaigns.report', compact('campaign', 'chartData', 'campaignLinks', 'uniqueOpenCount'));
    }
}

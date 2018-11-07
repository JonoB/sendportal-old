<?php

namespace App\Http\Controllers;

use App\Interfaces\CampaignReportServiceInterface;
use App\Interfaces\CampaignRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Models\CampaignStatus;
use Illuminate\Http\RedirectResponse;

class CampaignReportsController extends Controller
{
    /**
     * @var CampaignRepositoryInterface
     */
    protected $campaignRepo;

    /**
     * @var CampaignRepositoryInterface
     */
    protected $campaignReportService;

    /**
     * CampaignsController constructor.
     *
     * @param CampaignRepositoryInterface $campaignRepository
     * @param TemplateRepositoryInterface $campaignRepository#
     */
    public function __construct(
        CampaignRepositoryInterface $campaignRepository,
        CampaignReportServiceInterface $campaignReportService
    )
    {
        $this->campaignRepo = $campaignRepository;
        $this->campaignReportService = $campaignReportService;
    }

    /**
     * Show campaign report view
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\View\View
     */
    public function report($id)
    {
        $campaign = $this->campaignRepo->with('email')->find($id);

        if ($campaign->email->status_id == CampaignStatus::STATUS_DRAFT)
        {
            return redirect()->route('campaigns.edit', $id);
        }

        if ($campaign->email->status_id != CampaignStatus::STATUS_SENT)
        {
            return redirect()->route('campaigns.status', $id);

        }

        $chartData = $this->campaignReportService->opensPerHour($id);
        $campaignUrls = $this->campaignReportService->campaignUrls($id);

        return view('campaigns.report', compact('campaign', 'chartData', 'campaignUrls'));

    }

    /**
     * Show campaign recipients view
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\View\View
     */
    public function recipients($id)
    {
        $campaign = $this->campaignRepo->with('email')->find($id);

        if ($campaign->email->status_id == CampaignStatus::STATUS_DRAFT)
        {
            return redirect()->route('campaigns.edit', $id);
        }

        if ($campaign->email->status_id == CampaignStatus::STATUS_SENT)
        {
            $recipients = $this->contactCampaignRepo->paginate('created_at', [], 50, ['campaign_id' => $id]);

            return view('campaigns.recipients', compact('campaign', $recipients));
        }

        return redirect()->route('campaigns.status', $id);
    }

}

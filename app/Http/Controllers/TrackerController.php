<?php

namespace App\Http\Controllers;

use App\Interfaces\CampaignSubscriberRepositoryInterface;
use App\Interfaces\CampaignRepositoryInterface;
use App\Interfaces\CampaignUrlsRepositoryInterface;
use App\Repositories\CampaignUrlsEloquentRepository;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    /**
     * @var CampaignSubscriberRepositoryInterface
     */
    protected $subscriberCampaignRepo;

    /**
     * @var CampaignUrlsRepositoryInterface
     */
    protected $campaignUrlsRepo;

    /**
     * @var CampaignRepositoryInterface
     */
    protected $campaignRepo;

    /**
     * TrackerController constructor.
     *
     * @param CampaignSubscriberRepositoryInterface $subscriberCampaignRepo
     * @param CampaignRepositoryInterface $campaignRepository
     */
    public function __construct(
        CampaignSubscriberRepositoryInterface $subscriberCampaignRepo,
        CampaignUrlsRepositoryInterface $campaignUrlsRepository,
        CampaignRepositoryInterface $campaignRepository
    )
    {
        $this->subscriberCampaignRepo = $subscriberCampaignRepo;
        $this->campaignUrlsRepo = $campaignUrlsRepository;
        $this->campaignRepo = $campaignRepository;
    }

    /**
     * Track email opens
     *
     * @param Request $request
     * @param string $campaignId
     * @param string $subscriberId
     * @return void
     */
    public function opens(Request $request, $campaignId, $subscriberId)
    {

        header('Content-Type: image/gif');
        readfile(public_path('img/tracking.gif'));

        $this->subscriberCampaignRepo->incrementOpenCount($campaignId, $subscriberId, $request->ip());

        $totalOpenCount = $this->subscriberCampaignRepo->getUniqueOpenCount($campaignId);

        $this->campaignRepo->update($campaignId, [
            'open_count' => $totalOpenCount,
        ]);
    }

    /**
     * Track email clicks and redirect to original route
     *
     * @param Request $request
     * @param string $campaignId
     * @param string $subscriberId
     * @param string $urlId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function clicks(Request $request, $campaignId, $subscriberId, $urlId)
    {
        // store click count per url
        $this->campaignUrlsRepo->incrementClickCount($urlId);

        // store click count per user
        $this->subscriberCampaignRepo->incrementClickCount($campaignId, $subscriberId);

        $totalCampaignClickCount = $this->campaignUrlsRepo->getTotalClickCount($campaignId);

        $this->campaignRepo->update($campaignId, [
            'click_count' => $totalCampaignClickCount,
        ]);

        $campaignUrl = $this->campaignUrlsRepo->find($urlId);

        return redirect($campaignUrl->original_url);
    }
 }

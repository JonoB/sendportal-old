<?php

namespace App\Http\Controllers\Campaigns;

use App\Http\Controllers\Controller;
use App\Http\Requests\CampaignStoreRequest;
use App\Http\Requests\CampaignContentRequest;
use App\Http\Requests\CampaignTemplateUpdateRequest;
use App\Interfaces\CampaignRepositoryInterface;
use App\Interfaces\CampaignSubscriberTenantRepository;
use App\Interfaces\ProviderRepositoryInterface;
use App\Interfaces\SegmentRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Models\CampaignStatus;
use App\Repositories\CampaignTenantRepository;
use App\Repositories\ProviderTenantRepository;
use App\Repositories\SegmentTenantRepository;
use App\Repositories\TemplateTenantRepository;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignsController extends Controller
{
    /**
     * @var CampaignSubscriberTenantRepository
     */
    protected $campaignSubscribers;

    /**
     * @var CampaignTenantRepository
     */
    protected $campaigns;

    /**
     * @var TemplateTenantRepository
     */
    protected $templates;

    /**
     * @var SegmentTenantRepository
     */
    protected $segments;

    /**
     * @var ProviderTenantRepository
     */
    protected $providers;

    /**
     * CampaignsController constructor
     *
     * @param CampaignTenantRepository $campaigns
     * @param CampaignSubscriberTenantRepository $campaignSubscribers
     * @param TemplateTenantRepository $templates
     * @param SegmentTenantRepository $segments
     * @param ProviderTenantRepository $providers
     */
    public function __construct(
        CampaignTenantRepository $campaigns,
        CampaignSubscriberTenantRepository $campaignSubscribers,
        TemplateTenantRepository $templates,
        SegmentTenantRepository $segments,
        ProviderTenantRepository $providers
    )
    {
        $this->campaigns = $campaigns;
        $this->campaignSubscribers = $campaignSubscribers;
        $this->templates = $templates;
        $this->segments = $segments;
        $this->providers = $providers;
    }

    /**
     * Index of campaigns
     *
     * @return \Illuminate\Contracts\View\Factory|View
     * @throws \Exception
     */
    public function index()
    {
        $campaigns = $this->campaigns->paginate(currentTeamId(), 'created_atDesc', ['status']);
        $providerCount = $this->providers->count(currentTeamId());

        return view('campaigns.index', compact('campaigns', 'providerCount'));
    }

    /**
     * Create a new campaign
     *
     * @return \Illuminate\Contracts\View\Factory|View
     * @throws \Exception
     */
    public function create()
    {
        $templates = $this->templates->pluck(currentTeamId());
        $providers = $this->providers->all(currentTeamId());

        return view('campaigns.create', compact('templates', 'providers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CampaignStoreRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(CampaignStoreRequest $request)
    {
        $campaign = $this->campaigns->store(currentTeamId(), $request->validated());

        return redirect()->route('campaigns.content.edit', $campaign->id);
    }

    /**
     * Show details for the campaign
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|View
     * @throws \Exception
     */
    public function show($id)
    {
        $campaign = $this->campaigns->find(currentTeamId(), $id);

        return view('campaigns.show', compact('campaign'));
    }

    /**
     * Show the form for editing the campaign
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|View
     * @throws \Exception
     */
    public function edit($id)
    {
        $campaign = $this->campaigns->find(currentTeam(), $id);
        $providers = $this->providers->all(currentTeamId());
        $templates = $this->templates->pluck(currentTeamId());

        return view('campaigns.edit', compact('campaign', 'providers', 'templates'));
    }

    /**
     * Update the campaign
     *
     * @param int $campaignId
     * @param CampaignStoreRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function update(int $campaignId, CampaignStoreRequest $request)
    {
        $campaign = $this->campaigns->update(currentTeamId(), $campaignId, $request->validated());

        return redirect()->route('campaigns.content.edit', $campaign->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // @todo we need to check campaign status here and
        // redirect if its not in draft
    }

    /**
     * Display the confirmation view.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|View
     * @throws \Exception
     */
    public function confirm($id)
    {
        $campaign = $this->campaigns->find(currentTeamId(), $id);

        if ($campaign->status_id > 1)
        {
            return redirect()->route('campaigns.status', $id);
        }

        $segments = $this->segments->all(currentTeamId(), 'name');

        return view('campaigns.confirm', compact('campaign', 'segments'));
    }

    /**
     * Dispatch the campaign
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws \Exception
     */
    public function send(Request $request, $id)
    {
        $campaign = $this->campaigns->find(currentTeamId(), $id);

        if ($campaign->status_id > CampaignStatus::STATUS_DRAFT)
        {
            return redirect()->route('campaigns.status', $id);
        }

        // @todo validation that at least one list has been selected

        $campaign->update([
            'scheduled_at' => Carbon::now(),
            'status_id' => CampaignStatus::STATUS_QUEUED,
        ]);

        $campaign->segments()->sync($request->get('lists'));

        return redirect()->route('campaigns.status', $id);
    }

    /**
     * Display the status for a campaign.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|View
     * @throws \Exception
     */
    public function status($id)
    {
        $campaign = $this->campaigns->find(currentTeamId(), $id, ['status']);

        return view('campaigns.status', compact('campaign'));
    }

    /**
     * Show campaign report view
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|View
     * @throws \Exception
     */
    public function report($id)
    {
        $campaign = $this->campaigns->find(currentTeamId(), $id, ['email']);

        if ($campaign->status_id == CampaignStatus::STATUS_DRAFT)
        {
            return redirect()->route('campaigns.edit', $id);
        }

        if ($campaign->status_id == CampaignStatus::STATUS_SENT)
        {
            return view('campaigns.report', compact('campaign', 'chartData'));
        }

        return redirect()->route('campaigns.status', $id);
    }

    /**
     * Show campaign recipients view
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|View
     * @throws \Exception
     */
    public function recipients($id)
    {
        $campaign = $this->campaigns->find(currentTeamId(), $id, ['status']);

        if ($campaign->status_id == CampaignStatus::STATUS_DRAFT)
        {
            return redirect()->route('campaigns.edit', $id);
        }

        if ($campaign->status_id == CampaignStatus::STATUS_SENT)
        {
            $recipients = $this->campaignSubscribers->paginate('created_at', [], 50, ['campaign_id' => $id]);

            return view('campaigns.recipients', compact('campaign', $recipients));
        }

        return redirect()->route('campaigns.status', $id);
    }
}

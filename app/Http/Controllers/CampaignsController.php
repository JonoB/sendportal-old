<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignStoreRequest;
use App\Http\Requests\CampaignContentRequest;
use App\Interfaces\CampaignRepositoryInterface;
use App\Interfaces\CampaignSubscriberRepositoryInterface;
use App\Interfaces\ProviderRepositoryInterface;
use App\Interfaces\SegmentRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Models\CampaignStatus;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignsController extends Controller
{
    /**
     * @var CampaignSubscriberRepositoryInterface
     */
    protected $campaignSubscribers;

    /**
     * @var CampaignRepositoryInterface
     */
    protected $campaigns;

    /**
     * @var TemplateRepositoryInterface
     */
    protected $templates;

    /**
     * @var SegmentRepositoryInterface
     */
    protected $segments;

    /**
     * @var ProviderRepositoryInterface
     */
    private $providers;

    /**
     * @param CampaignRepositoryInterface $campaigns
     * @param CampaignSubscriberRepositoryInterface $campaignSubscribers
     * @param TemplateRepositoryInterface $templates
     * @param SegmentRepositoryInterface $segments
     * @param ProviderRepositoryInterface $providers
     */
    public function __construct(
        CampaignRepositoryInterface $campaigns,
        CampaignSubscriberRepositoryInterface $campaignSubscribers,
        TemplateRepositoryInterface $templates,
        SegmentRepositoryInterface $segments,
        ProviderRepositoryInterface $providers
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
     * @return View
     */
    public function index()
    {
        $campaigns = $this->campaigns->paginate('created_atDesc', ['status']);
        $providerCount = $this->providers->count();

        return view('campaigns.index', compact('campaigns', 'providerCount'));
    }

    /**
     * Create a new campaign
     *
     * @return View
     */
    public function create()
    {
        $templatesAvailable = $this->templates->all()->count();
        $providers = $this->providers->all();

        return view('campaigns.create', compact('templatesAvailable', 'providers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CampaignStoreRequest $request
     *
     * @return RedirectResponse
     */
    public function store(CampaignStoreRequest $request)
    {
        $campaign = $this->campaigns->store($request->validated());

        return redirect()->route('campaigns.content.edit', $campaign->id);
    }

    /**
     * Show details for the campaign
     *
     * @param string $id
     *
     * @return View
     */
    public function show($id)
    {
        $campaign = $this->campaigns->find((int)$id);

        return view('campaigns.show', compact('campaign'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     *
     * @return View
     */
    public function edit($id)
    {
        $campaign = $this->campaigns->find($id);
        $providers = $this->providers->all();

        return view('campaigns.edit', compact('campaign', 'providers'));
    }

    /**
     * Update the campaign.
     *
     * @param int $campaignId
     * @param CampaignStoreRequest $request
     *
     * @return RedirectResponse
     */
    public function update(int $campaignId, CampaignStoreRequest $request)
    {
        $campaign = $this->campaigns->update($campaignId, $request->validated());

        return redirect()->route('campaigns.content.edit', $campaign->id);
    }

    /**
     * Show the form for editing campaign content
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function editContent($id)
    {
        $campaign = $this->campaigns->find(($id));

        return view('campaigns.content', compact('campaign'));
    }

    /**
     * Update the campaign content
     *
     * @param CampaignContentRequest $request
     * @param string $id
     *
     * @return RedirectResponse
     */
    public function updateContent(CampaignContentRequest $request, $id)
    {
        $campaign = $this->campaigns->find($id);

        if ($campaign->status_id !== CampaignStatus::STATUS_DRAFT)
        {
            return redirect()
                ->route('campaigns.report', $campaign->id);
        }

        $campaign = $this->campaigns->update($id, $request->only('content'));

        return redirect()->route('campaigns.confirm', $campaign->id);
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
        //
    }

    /**
     * Display the confirmation view.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function confirm($id)
    {
        $campaign = $this->campaigns->find($id, ['email']);

        if ($campaign->status_id > 1)
        {
            return redirect()->route('campaigns.status', $id);
        }

        $template = $this->templates->find($campaign->email->template_id);
        $segments = $this->segments->all('name');

        return view('campaigns.confirm', compact('campaign', 'template', 'segments'));
    }

    /**
     * Dispatch the campaign.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request, $id)
    {
        $campaign = $this->campaigns->find($id);

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
     * Display the status view.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        $campaign = $this->campaigns->find($id, ['status']);

        return view('campaigns.status', compact('campaign'));
    }

    /**
     * Show campaign report view
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|View
     */
    public function report($id)
    {
        $campaign = $this->campaigns->find($id, ['email']);

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
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|View
     */
    public function recipients($id)
    {
        $campaign = $this->campaigns->find($id, ['status']);

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

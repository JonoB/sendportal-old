<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignRequest;
use App\Http\Requests\CampaignStoreRequest;
use App\Interfaces\CampaignRepositoryInterface;
use App\Interfaces\CampaignSubscriberRepositoryInterface;
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
     * @param CampaignRepositoryInterface $campaigns
     * @param CampaignSubscriberRepositoryInterface $campaignSubscribers
     * @param TemplateRepositoryInterface $templates
     * @param SegmentRepositoryInterface $segments
     */
    public function __construct(
        CampaignRepositoryInterface $campaigns,
        CampaignSubscriberRepositoryInterface $campaignSubscribers,
        TemplateRepositoryInterface $templates,
        SegmentRepositoryInterface $segments
    )
    {
        $this->campaigns = $campaigns;
        $this->campaignSubscribers = $campaignSubscribers;
        $this->templates = $templates;
        $this->segments = $segments;
    }

    /**
     * Fields that belong to a campaign instead of an email.
     *
     * @var array
     */
    protected $campaignFields = [
        'name',
        'status_id',
        'scheduled_at',
    ];

    /**
     * Index of campaigns
     *
     * @return View
     */
    public function index()
    {
        $campaigns = $this->campaigns->paginate('created_atDesc', ['status', 'email']);

        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * Create a new campaign
     *
     * @return View
     */
    public function create()
    {
        $templatesAvailable = $this->templates->all()->count();

        return view('campaigns.create', compact('templatesAvailable'));
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

        return redirect()
            ->route('campaigns.emails.create', $campaign->id);
    }

    /**
     * Show details for the campaign
     *
     * @param string $id
     *
     * @return void
     */
    public function show($id)
    {
        dd('TODO(david): implement the show view');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campaign = $this->campaigns->find($id);

        if ( ! isset($campaign->email))
        {
            return redirect(route('campaigns.emails.create', ['campaign' => $campaign->id]));
        }

        elseif ( ! $campaign->status_id == CampaignStatus::STATUS_DRAFT)
        {
            return redirect(route('campaign.index'));
        }

        return view('campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CampaignRequest $request
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function update(CampaignRequest $request, $id)
    {
        $campaign = $this->campaigns->find($id);

        if ( ! $campaign->status_id == CampaignStatus::STATUS_DRAFT)
        {
            return redirect(route('campaign.index'));
        }

        $emailUpdateFields = [
            'subject',
            'from_email',
            'from_name',
        ];

        $this->campaigns->update($id, $request->only($this->campaignFields));
        $campaign->email()->update($request->only($emailUpdateFields));

        return redirect()->route('campaigns.index');
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
        $lists = $this->segments->all('name', ['subscriberCount']);

        return view('campaigns.confirm', compact('campaign', 'template', 'lists'));
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

        if ($campaign->status_id > 1)
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

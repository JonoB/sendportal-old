<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignRequest;
use App\Interfaces\CampaignSubscriberRepositoryInterface;
use App\Interfaces\SegmentRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Interfaces\CampaignRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Models\CampaignStatus;
use App\Services\CampaignReportService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CampaignsController extends Controller
{
    /**
     * @var CampaignSubscriberRepositoryInterface
     */
    protected $campaignSubscriberRepo;

    /**
     * @var CampaignRepositoryInterface
     */
    protected $campaignRepo;

    /**
     * @var TemplateRepositoryInterface
     */
    protected $templateRepo;

    /**
     * @var SegmentRepositoryInterface
     */
    protected $segment;

    /**
     * CampaignsController constructor.
     *
     * @param CampaignRepositoryInterface $campaignRepository
     * @param CampaignSubscriberRepositoryInterface $campaignSubscriberRepository
     * @param TemplateRepositoryInterface $templateRepository
     * @param SegmentRepositoryInterface $segment
     */
    public function __construct(
        CampaignRepositoryInterface $campaignRepository,
        CampaignSubscriberRepositoryInterface $campaignSubscriberRepository,
        TemplateRepositoryInterface $templateRepository,
        SegmentRepositoryInterface $segment
    )
    {
        $this->campaignRepo = $campaignRepository;
        $this->campaignSubscriberRepo = $campaignSubscriberRepository;
        $this->templateRepo = $templateRepository;
        $this->segment = $segment;
    }

    /**
     * Fields that belong to a campaign instead of an email.
     *
     * @var array
     */
    protected $campaignFields = [
        'name',
        'scheduled_at',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns = $this->campaignRepo->paginate('created_atDesc', ['status', 'email']);

        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $templatesAvailable = $this->templateRepo->all()->count();

        return view('campaigns.create', compact('templatesAvailable'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CampaignRequest $request
     *
     * @return RedirectResponse
     */
    public function store(CampaignRequest $request)
    {
        $campaign = $this->campaignRepo->store($request->only($this->campaignFields));
        $campaign->email()->create($request->except($this->campaignFields));

        return redirect()->route('campaigns.template', $campaign->id);
    }

    /**
     * Display a list of templates for selection.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function template($id)
    {
        $campaign = $this->campaignRepo->find($id);

        // @todo fix the pagination in the view
        $templates = $this->templateRepo->paginate();

        return view('campaigns.template', compact('campaign', 'templates'));
    }

    /**
     * Update the template.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function updateTemplate(Request $request, $id)
    {
        $campaign = $this->campaignRepo->find($id);
        $templateId = $request->get('template_id');

        if ( ! $templateId)
        {
            return redirect()->back()->withErrors([
                'msg' => 'Your must select a template to use for this campaign',
            ]);
        }

        $template = $this->templateRepo->find($templateId);

        // @todo at this point we're just over-writing the campaign
        // content with the template content, but we need to cater for the
        // case when the user doesn't actually want to overwrite the campaign content
        $campaign->email()->update([
            'content' => $template->content,
            'template_id' => $templateId,
        ]);

        return redirect()->route('campaigns.design', $id);
    }

    /**
     * Display the template for design.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function design($id)
    {
        $campaign = $this->campaignRepo->find($id);
        $template = $this->templateRepo->find($campaign->email->template_id);

        return view('campaigns.design', compact('campaign', 'template'));
    }

    /**
     * Update the design.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function updateDesign(Request $request, $id)
    {
        $this->campaignRepo->update($id, $request->only('content'));

        return redirect()->route('campaigns.confirm', $id);
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
        $campaign = $this->campaignRepo->find($id);

        if ($campaign->email->status_id > 1)
        {
            return redirect()->route('campaigns.status', $id);
        }

        $template = $this->templateRepo->find($campaign->email->template_id);
        $lists = $this->segment->all('name', ['subscriberCount']);

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
        $campaign = $this->campaignRepo->find($id);

        if ($campaign->email->status_id > 1)
        {
            return redirect()->route('campaigns.status', $id);
        }

        // @todo validation that at least one list has been selected

        $campaign->update([
            'scheduled_at' => Carbon::now(),
        ]);

        $campaign->email()->update([
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
        $campaign = $this->campaignRepo->find($id, ['status']);

        return view('campaigns.status', compact('campaign'));
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
        $campaign = $this->campaignRepo->with('email')->find($id);

        if ( ! $campaign->email->status == CampaignStatus::STATUS_DRAFT)
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
        $campaign = $this->campaignRepo->find($id);

        if ( ! $campaign->email->status == CampaignStatus::STATUS_DRAFT)
        {
            return redirect(route('campaign.index'));
        }

        $emailUpdateFields = [
            'subject',
            'from_email',
            'from_name',
        ];

        $this->campaignRepo->update($id, $request->only($this->campaignFields));
        $campaign->email()->update($request->only($emailUpdateFields));

        return redirect()->route('campaigns.template', $campaign->id);
    }

    /**
     * Show campaign report view
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\View\View
     */
    public function report($id)
    {
        $campaign = $this->campaignRepo->with('email')->find($id);

        if ($campaign->email->status_id == CampaignStatus::STATUS_DRAFT)
        {
            return redirect()->route('campaigns.edit', $id);
        }

        if ($campaign->email->status_id == CampaignStatus::STATUS_SENT)
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
            $recipients = $this->campaignSubscriberRepo->paginate('created_at', [], 50, ['campaign_id' => $id]);

            return view('campaigns.recipients', compact('campaign', $recipients));
        }

        return redirect()->route('campaigns.status', $id);
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
}

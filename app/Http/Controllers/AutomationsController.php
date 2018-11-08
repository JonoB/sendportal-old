<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignRequest;
use App\Interfaces\CampaignSubscriberRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Models\CampaignStatus;
use App\Services\CampaignReportService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AutomationsController extends Controller
{


    /**
     * CampaignsController constructor.
     *
     * @param CampaignRepositoryInterface $campaignRepository
     * @param CampaignSubscriberRepositoryInterface $campaignSubscriberRepository
     * @param TemplateRepositoryInterface $templateRepository
     */
    public function __construct()
    {
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd('todo');

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
        dd('todo');

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
        dd('todo');

        $campaign = $this->campaignRepo->store($request->only($this->campaignFields));
        $campaign->email()->create($request->except($this->campaignFields));

        return redirect()->route('campaigns.template', $campaign->id);
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
        dd('todo');

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
        dd('todo');

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
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd('todo');
    }
}

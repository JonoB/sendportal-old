<?php

namespace App\Http\Controllers;

use App\Interfaces\AutomationRepositoryInterface;
use App\Interfaces\SegmentRepositoryInterface;
use App\Http\Requests\CampaignRequest;
use App\Interfaces\CampaignSubscriberTenantRepository;
use App\Interfaces\TemplateRepositoryInterface;
use App\Models\CampaignStatus;
use App\Repositories\AutomationTenantRepository;
use App\Repositories\ProviderTenantRepository;
use App\Repositories\SegmentTenantRepository;
use App\Services\CampaignReportService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AutomationsController extends Controller
{
    /**
     * @var SegmentTenantRepository
     */
    private $segmentRepository;

    /**
     * @var AutomationTenantRepository
     */
    private $automationRepository;

    /**
     * @var ProviderTenantRepository
     */
    private $providerRepository;

    /**
     * AutomationsController constructor.
     *
     * @param SegmentTenantRepository $segmentRepository
     * @param AutomationTenantRepository $automationRepository
     */
    public function __construct(
        SegmentTenantRepository $segmentRepository,
        AutomationTenantRepository $automationRepository,
        ProviderTenantRepository $providerRepository
    )
    {
        $this->segmentRepository = $segmentRepository;
        $this->automationRepository = $automationRepository;
        $this->providerRepository = $providerRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $automations = $this->automationRepository->paginate(currentTeamId());

        return view('automations.index', compact('automations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $segments = $this->segmentRepository->pluck(currentTeamId());
        $providers = $this->providerRepository->pluck(currentTeamId());

        return view('automations.create', compact('segments', 'providers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'provider_id' => 'required|int',
            'from_name' => 'required',
            'from_email' => 'required|email',
        ]);

        $automation = $this->automationRepository->store(currentTeamId(), $request->all());

        return redirect(route('automations.show', $automation->id));
    }

    /**
     * Edit the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $automation = $this->automationRepository->find(currentTeamId(), $id, ['automation_steps.template']);

        return view('automations.show', compact('automation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        //
    }
}

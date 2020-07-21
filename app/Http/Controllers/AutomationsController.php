<?php

namespace App\Http\Controllers;

use App\Repositories\AutomationTenantRepository;
use App\Repositories\ProviderTenantRepository;
use App\Repositories\SegmentTenantRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

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
     * @param ProviderTenantRepository $providerRepository
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
     * @return Factory|View
     * @throws Exception
     */
    public function index()
    {
        $automations = $this->automationRepository->paginate(currentTeamId());

        return view('automations.index', compact('automations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     * @throws Exception
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
     * @param Request $request
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'provider_id' => 'required|int',
            'from_name' => 'required',
            'from_email' => 'required|email',
        ]);

        $automation = $this->automationRepository->store(currentTeamId(), $request->all());

        return redirect()->route('automations.show', $automation->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Factory|View
     * @throws Exception
     */
    public function show(int $id)
    {
        $automation = $this->automationRepository->find(currentTeamId(), $id, ['automation_steps.template']);

        return view('automations.show', compact('automation'));
    }
}

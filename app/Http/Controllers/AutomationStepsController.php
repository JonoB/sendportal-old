<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutomationStepRequest;
use App\Repositories\AutomationStepEloquentRepository;
use App\Repositories\AutomationTenantRepository;
use App\Repositories\TemplateTenantRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AutomationStepsController extends Controller
{
    /**
     * @var AutomationTenantRepository
     */
    protected $automations;

    /**
     * @var AutomationStepEloquentRepository
     */
    protected $automationSteps;

    /**
     * @var TemplateTenantRepository
     */
    protected $templates;

    /**
     * AutomationStepsController constructor.
     *
     * @param AutomationTenantRepository $automations
     * @param AutomationStepEloquentRepository $automationSteps
     * @param TemplateTenantRepository $templates
     */
    public function __construct(
        AutomationTenantRepository $automations,
        AutomationStepEloquentRepository $automationSteps,
        TemplateTenantRepository $templates)
    {
        $this->automations = $automations;
        $this->automationSteps = $automationSteps;
        $this->templates = $templates;
    }

    /**
     * @throws Exception
     */
    public function create(int $automationId)
    {
        $automation = $this->automations->find(currentTeamId(), $automationId);
        $templates = $this->templates->pluck(currentTeamId());

        return view('automations.steps.create', compact('automation', 'templates'));
    }

    /**
     * @throws Exception
     */
    public function store(AutomationStepRequest $request, int $automationId)
    {
        $data = $request->validated();
        $this->automations->find(currentTeamId(), $automationId);
        $data['automation_id'] = $automationId;
        $this->automationSteps->store($data);

        return redirect()->route('automations.show', $automationId);
    }

    /**
     * @throws Exception
     */
    public function edit(int $automationId, int $stepId)
    {
        $this->automations->find(currentTeamId(), $automationId);
        $automationStep = $this->automationSteps->find($stepId);
        $templates = $this->templates->pluck(currentTeamId());

        return view('automations.steps.edit', compact('automationStep', 'templates'));
    }

    /**
     * @throws Exception
     */
    public function update(AutomationStepRequest $request, int $automationId, int $stepId)
    {
        $this->automations->find(currentTeamId(), $automationId);
        $this->automationSteps->update($stepId, $request->validated());

        return redirect()->route('automations.show', $automationId);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutomationStepRequest;
use App\Repositories\AutomationStepEloquentRepository;
use App\Repositories\AutomationTenantRepository;
use App\Repositories\TemplateTenantRepository;

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

    public function create($automationId)
    {
        $automation = $this->automations->find(currentTeamId(), $automationId);
        $templates = $this->templates->pluck(currentTeamId());

        return view('automations.steps.create', compact('automation', 'templates'));
    }

    public function store(AutomationStepRequest $request, $automationId)
    {
        $data = $request->validated();

        // tenant check
        $this->automations->find(currentTeamId(), $automationId);

        $data['automation_id'] = $automationId;

        $this->automationSteps->store($data);

        return redirect()->route('automations.show', $automationId);
    }

    public function edit($automationId, $stepId)
    {
        // tenant check
        $this->automations->find(currentTeamId(), $automationId);
        
        $automationStep = $this->automationSteps->find($stepId);
        $templates = $this->templates->pluck(currentTeamId());

        return view('automations.steps.edit', compact('automationStep', 'templates'));
    }

    public function update(AutomationStepRequest $request, $automationId, $stepId)
    {
        // tenant check
        $this->automations->find(currentTeamId(), $automationId);

        $this->automationSteps->update($stepId, $request->validated());

        return redirect()->route('automations.show', $automationId);
    }
}

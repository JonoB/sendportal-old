<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutomationStepRequest;
use App\Interfaces\AutomationRepositoryInterface;
use App\Interfaces\EmailRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Models\Automation;
use App\Repositories\AutomationStepEloquentRepository;

class AutomationStepsController extends Controller
{
    /**
     * @var AutomationRepositoryInterface
     */
    protected $automations;

    /**
     * @var AutomationStepEloquentRepository
     */
    protected $automationSteps;

    /**
     * @var TemplateRepositoryInterface
     */
    protected $templates;

    /**
     * @var EmailRepositoryInterface
     */
    protected $emails;

    /**
     * AutomationStepsController constructor.
     *
     * @param AutomationStepEloquentRepository $automationSteps
     * @param TemplateRepositoryInterface $templates
     */
    public function __construct(AutomationRepositoryInterface $automations, AutomationStepEloquentRepository $automationSteps, TemplateRepositoryInterface $templates)
    {
        $this->automations = $automations;
        $this->automationSteps = $automationSteps;
        $this->templates = $templates;
    }

    public function create($automationId)
    {
        $automation = $this->automations->find($automationId);
        $templates = $this->templates->pluck();

        return view('automations.steps.create', compact('automation', 'templates'));
    }

    public function store(AutomationStepRequest $request, $automationId)
    {
        $data = $request->validated();
        $data['automation_id'] = $automationId;

        $this->automationSteps->store($data);

        return redirect()->route('automations.show', $automationId);
    }

    public function edit($automationId, $stepId)
    {
        $automationStep = $this->automationSteps->find($stepId);
        $templates = $this->templates->pluck();

        return view('automations.steps.edit', compact('automationStep', 'templates'));
    }

    public function update(AutomationStepRequest $request, $automationId, $stepId)
    {
        $this->automationSteps->update($stepId, $request->validated());

        return redirect()->route('automations.show', $automationId);
    }
}

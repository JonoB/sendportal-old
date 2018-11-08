<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutomationStepStoreRequest;
use App\Http\Requests\AutomationStepUpdateRequest;
use App\Interfaces\AutomationRepositoryInterface;
use App\Interfaces\AutomationStepRepositoryInterface;
use App\Models\AutomationStep;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AutomationStepsController extends Controller
{
    /**
     * @var AutomationRepositoryInterface
     */
    private $automations;

    /**
     * @var AutomationStepRepositoryInterface
     */
    private $automationSteps;

    /**
     * AutomationStepsController constructor.
     *
     * @param AutomationRepositoryInterface $automations
     * @param AutomationStepRepositoryInterface $automationSteps
     */
    public function __construct(AutomationRepositoryInterface $automations, AutomationStepRepositoryInterface $automationSteps)
    {
        $this->automations = $automations;
        $this->automationSteps = $automationSteps;
    }

    public function edit(int $automationId, int $automationStepId)
    {
        $automationStep = $this->automationSteps->findStepForAutomation($automationId, $automationStepId);
        $automationUnits = AutomationStep::$units;
        return view('automations.steps.edit', compact('automationStep', 'automationUnits'));
    }

    public function create(int $automationId)
    {
        $automation = $this->automations->find($automationId);
        $automationUnits = AutomationStep::$units;
        return view('automations.steps.create', compact('automation', 'automationUnits'));
    }

    public function update(AutomationStepUpdateRequest $request, int $automationId, int $automationStepId)
    {
        $this->automationSteps->updateStepForAutomation($automationId, $automationStepId, $request->validated());
        return redirect()
            ->route('automations.steps.edit', [$automationId, $automationStepId])
            ->with('success', 'Updated automation step.');

    }

    public function store(AutomationStepStoreRequest $request, int $automationId)
    {
        $automationStep = $this->automations->find($automationId)->steps()->create($request->validated());
        return redirect(route('automations.steps.email.create', [$automationId, $automationStep->id]));
    }
}

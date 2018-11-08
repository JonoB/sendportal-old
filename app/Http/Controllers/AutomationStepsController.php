<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutomationStepStoreRequest;
use App\Interfaces\AutomationRepositoryInterface;
use App\Interfaces\AutomationStepRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AutomationStepsController extends Controller
{
    /**
     * @var AutomationRepositoryInterface
     */
    private $automations;

    /**
     * AutomationStepsController constructor.
     *
     * @param AutomationRepositoryInterface $automations
     */
    public function __construct(AutomationRepositoryInterface $automations)
    {
        $this->automations = $automations;
    }

    public function create(int $automationId)
    {
        $automation = $this->automations->find($automationId);
        return view('automations.steps.create', compact('automation'));
    }

    public function store(AutomationStepStoreRequest $request, int $automationId)
    {
        $automationStep = $this->automations->find($automationId)->steps()->create($request->validated());
        return redirect(route('automations.steps.email.create', [$automationId, $automationStep->id]));
    }
}

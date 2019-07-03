<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutomationStepStoreRequest;
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

    public function store(AutomationStepStoreRequest $request, $automationId)
    {
        $data = $request->validated();
        $data['automation_id'] = $automationId;

        $this->automationSteps->store($data);

        return redirect()->route('automations.show', [$automationId]);
    }

    public function edit($automationId, $emailId)
    {
        $email = $this->emails->findAutomationEmail($automationId, $emailId);
        $automation = $email->mailable;

        return view('automations.steps.edit', compact('email', 'automation'));
    }

    public function update(AutomationEmailUpdateRequest $request, $automationId)
    {
        $this->emailService->update('automation', $automationId, $request->validated());

        return redirect()->route('automations.show', $automationId);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutomationEmailStoreRequest;
use App\Interfaces\AutomationRepositoryInterface;
use App\Interfaces\AutomationStepRepositoryInterface;
use App\Interfaces\EmailRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Models\Automation;
use App\Models\AutomationStep;

class AutomationStepEmailController extends Controller
{
    /**
     * @var TemplateRepositoryInterface
     */
    private $templates;

    /**
     * @var EmailRepositoryInterface
     */
    private $emails;

    /**
     * @var AutomationStepRepositoryInterface
     */
    private $automationSteps;

    /**
     * @var AutomationRepositoryInterface
     */
    private $automations;

    /**
     * AutomationEmailsController constructor.
     *
     * @param AutomationStepRepositoryInterface $automationSteps
     * @param TemplateRepositoryInterface $templates
     * @param EmailRepositoryInterface $emails
     * @param AutomationRepositoryInterface $automations
     */
    public function __construct(
        AutomationStepRepositoryInterface $automationSteps,
        TemplateRepositoryInterface $templates,
        EmailRepositoryInterface $emails,
        AutomationRepositoryInterface $automations
    )
    {
        $this->templates = $templates;
        $this->emails = $emails;
        $this->automationSteps = $automationSteps;
        $this->automations = $automations;
    }

    public function create($automationId, $automationStepId)
    {
        $automationStep = $this->automationSteps->findStepForAutomation($automationId, $automationStepId);

        $templates = $this->templates->pluck();

        return view('automations.steps.email.create', compact('automationStep', 'templates'));
    }

    public function store(AutomationEmailStoreRequest $request, $automationId, $automationStepId)
    {
        $email = $this->emails->storeMailable(AutomationStep::class, $automationStepId, $request->validated());

        return redirect()->route('automations.steps.email.content.edit', [$email->mailable->automation->id, $email->mailable->id]);
    }
    /*

    public function edit($automationId, $emailId)
    {
        $email = $this->emails->find($emailId);
        $automation = $email->mailable;

        return view('automations.emails.edit', compact('email', 'automation'));
    }

    public function update(AutomationEmailUpdateRequest $request, $automationId)
    {
        $this->emailService->update('automation', $automationId, $request->validated());

        return redirect()->route('automations.show', $automationId);
    }

    */
}

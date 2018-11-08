<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutomationEmailStoreRequest;
use App\Interfaces\AutomationRepositoryInterface;
use App\Interfaces\EmailRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Models\Automation;

class AutomationEmailsController extends Controller
{
    /**
     * @var AutomationRepositoryInterface
     */
    private $automations;

    /**
     * @var TemplateRepositoryInterface
     */
    private $templates;

    /**
     * @var EmailRepositoryInterface
     */
    private $emails;

    /**
     * AutomationEmailsController constructor.
     *
     * @param AutomationRepositoryInterface $automations
     * @param TemplateRepositoryInterface $templates
     * @param EmailRepositoryInterface $emails
     */
    public function __construct(AutomationRepositoryInterface $automations, TemplateRepositoryInterface $templates, EmailRepositoryInterface $emails)
    {
        $this->automations = $automations;
        $this->templates = $templates;
        $this->emails = $emails;
    }

    public function create($automationId)
    {
        $automation = $this->automations->find($automationId);
        $templates = $this->templates->pluck();

        return view('automations.emails.create', compact('automation', 'templates'));
    }

    public function store(AutomationEmailStoreRequest $request, $automationId)
    {
        $email = $this->emails->storeMailable(Automation::class, $automationId, $request->validated());

        return redirect()->route('automations.emails.content.edit', [$automationId, $email->id]);
    }

    public function edit($automationId, $emailId)
    {
        $email = $this->emails->findAutomationEmail($automationId, $emailId);
        $automation = $email->mailable;

        return view('automations.emails.edit', compact('email', 'automation'));
    }

    public function update(AutomationEmailUpdateRequest $request, $automationId)
    {
        $this->emailService->update('automation', $automationId, $request->validated());

        return redirect()->route('automations.show', $automationId);
    }
}

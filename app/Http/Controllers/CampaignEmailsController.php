<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutomationEmailStoreRequest;
use App\Interfaces\CampaignRepositoryInterface;
use App\Interfaces\EmailRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Models\Campaign;

class CampaignEmailsController extends Controller
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
     * @var CampaignRepositoryInterface
     */
    private $campaigns;

    /**
     * AutomationEmailsController constructor.
     *
     * @param CampaignRepositoryInterface $campaigns
     * @param TemplateRepositoryInterface $templates
     * @param EmailRepositoryInterface $emails
     */
    public function __construct(CampaignRepositoryInterface $campaigns, TemplateRepositoryInterface $templates, EmailRepositoryInterface $emails)
    {
        $this->campaigns = $campaigns;
        $this->templates = $templates;
        $this->emails = $emails;
    }

    public function create($campaignId)
    {
        $campaign = $this->campaigns->find($campaignId);
        $templates = $this->templates->pluck();

        return view('campaigns.emails.create', compact('campaign', 'templates'));
    }

    public function store(AutomationEmailStoreRequest $request, $automationId)
    {
        $email = $this->emails->storeMailable(Campaign::class, $automationId, $request->validated());

        return redirect()->route('campaigns.emails.content.edit', [$automationId, $email->id]);
    }

    public function edit($automationId, $emailId)
    {
        $email = $this->emails->findAutomationEmail($automationId, $emailId);
        $automation = $email->mailable;

        return view('campaigns.emails.edit', compact('email', 'automation'));
    }

    public function update(AutomationEmailUpdateRequest $request, $automationId)
    {
        $this->emailService->update('automation', $automationId, $request->validated());

        return redirect()->route('campaigns.show', $automationId);
    }
}

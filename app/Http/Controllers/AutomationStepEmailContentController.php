<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutomationStepEmailContentUpdateRequest;
use App\Interfaces\EmailRepositoryInterface;
use Illuminate\Http\Request;

class AutomationStepEmailContentController extends Controller
{
    /**
     * @var EmailRepositoryInterface
     */
    private $emails;

    /**
     * AutomationStepEmailContentController constructor.
     *
     * @param EmailRepositoryInterface $emails
     */
    public function __construct(EmailRepositoryInterface $emails)
    {
        $this->emails = $emails;
    }

    /**
     * Edit automation step email content.
     * @param $automationId
     * @param $automationStepId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($automationId, $automationStepId)
    {
        $email = $this->emails->findAutomationStepEmail((int)$automationStepId, ['mailable', 'template']);

        return view('automations.steps.email.content.edit', compact('email'));
    }

    /**
     * Update automation step email content
     *
     * @param AutomationStepEmailContentUpdateRequest $request
     * @param $automationId
     * @param $automationStepId
     *
     * @return RedirectResponse
     */
    public function update(AutomationStepEmailContentUpdateRequest $request, $automationId, $automationStepId)
    {
        $this->emails->updateAutomationStepEmail((int)$automationStepId, $request->validated());
        return redirect()
            ->route('automations.show', $automationId)
            ->with('success', 'Updated email content.');
    }
}

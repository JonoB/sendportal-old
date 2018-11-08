<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignEmailContentUpdateRequest;
use App\Interfaces\EmailRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CampaignEmailContentController extends Controller
{
    /**
     * @var EmailRepositoryInterface
     */
    protected $emails;

    /**
     * @param EmailRepositoryInterface $emails
     */
    public function __construct(EmailRepositoryInterface $emails)
    {
        $this->emails = $emails;
    }

    /**
     * Edit a campaign email's content
     *
     * @param string $campaignId
     * @param string $emailId
     *
     * @return View
     */
    public function edit($campaignId)
    {
        $email = $this->emails->findCampaignEmail((int)$campaignId, ['mailable', 'template']);

        return view('campaigns.emails.content.edit', compact('email'));
    }

    /**
     * Update campaign email content
     *
     * @param CampaignEmailContentUpdateRequest $request
     * @param string $campaignId
     * @param string $emailId
     *
     * @return RedirectResponse
     */
    public function update(CampaignEmailContentUpdateRequest $request, $campaignId)
    {
        $input = $request->validated();

        $this->emails->updateCampaignEmail((int)$campaignId, $input);

        return redirect()
            ->route('campaigns.show', $campaignId)
            ->with('success', 'Updated email content.');
    }
}

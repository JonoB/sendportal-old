<?php

namespace App\Http\Controllers\Campaigns;

use App\Http\Controllers\Controller;
use App\Http\Requests\CampaignContentRequest;
use App\Models\CampaignStatus;
use App\Repositories\CampaignTenantRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CampaignContentController extends Controller
{
    /**
     * @var CampaignTenantRepository
     */
    protected $campaigns;

    /**
     * CampaignsContentController constructor
     *
     * @param CampaignTenantRepository $campaigns
     */
    public function __construct(
        CampaignTenantRepository $campaigns
    )
    {
        $this->campaigns = $campaigns;
    }

    /**
     * Show the form for editing campaign content
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|View
     * @throws \Exception
     */
    public function edit(int $id)
    {
        $campaign = $this->campaigns->find(currentTeamId(), $id, ['template']);

        if ($campaign->sent)
        {
            return redirect()->route('campaigns.report', $id);
        }

        return view('campaigns.content', compact('campaign'));
    }

    /**
     * Update the campaign content
     *
     * @param CampaignContentRequest $request
     * @param $id
     * @return RedirectResponse
     * @throws \Exception
     */
    public function update(CampaignContentRequest $request, $id)
    {
        $campaign = $this->campaigns->find(currentTeamId(), $id);

        if ($campaign->status_id !== CampaignStatus::STATUS_DRAFT)
        {
            return redirect()
                ->route('campaigns.report', $campaign->id);
        }

        $campaign = $this->campaigns->update(currentTeamId(), $id, $request->only('content'));

        return redirect()->route('campaigns.confirm', $campaign->id);
    }
}

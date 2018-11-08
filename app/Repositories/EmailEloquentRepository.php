<?php

namespace App\Repositories;

use App\Interfaces\EmailRepositoryInterface;
use App\Models\Automation;
use App\Models\Campaign;
use App\Models\CampaignStatus;
use App\Models\Email;

class EmailEloquentRepository extends BaseEloquentRepository implements EmailRepositoryInterface
{
    protected $modelName = Email::class;

    public function queuedCampaigns()
    {
        return $this->getQueryBuilder()
            ->where('status_id', CampaignStatus::STATUS_QUEUED)
            ->where('mailable_type', 'App\Models\Campaign')
            ->with('mailable.segments')
            ->get();
    }

    /**
     * Store an email linked against a polymorphic type
     *
     * @param string $morphType
     * @param int $morphId
     * @param array $data
     *
     * @return Email
     */
    public function storeMailable(string $morphType, int $morphId, array $data): Email
    {
        $data['mailable_type'] = $morphType;
        $data['mailable_id'] = $morphId;

        return $this->store($data);
    }

    /**
     * Find an email ensuring it is associated with the given campaign
     *
     * @param int $campaignId
     * @param int $emailId
     * @param array $relations
     *
     * @return Email
     */
    public function findCampaignEmail(int $campaignId, int $emailId, array $relations = []): Email
    {
        return $this->getQueryBuilder()
            ->where('mailable_id', $campaignId)
            ->where('mailable_type', Campaign::class)
            ->where('id', $emailId)
            ->with($relations)
            ->firstOrFail();
    }
}

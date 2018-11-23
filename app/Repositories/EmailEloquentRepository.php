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
     * Find the email associated with the given campaign
     *
     * @param int $campaignId
     * @param array $relations
     *
     * @return Email
     */
    public function findCampaignEmail(int $campaignId, array $relations = []): Email
    {
        return $this->getQueryBuilder()
            ->where('mailable_id', $campaignId)
            ->where('mailable_type', Campaign::class)
            ->with($relations)
            ->firstOrFail();
    }

    /**
     * Update a campaign's email
     *
     * @param int $campaignId
     * @param array $data
     *
     * @return Email
     */
    public function updateCampaignEmail(int $campaignId, array $data): Email
    {
        $email = $this->findCampaignEmail($campaignId);

        $email->update($data);

        return $email;
    }
}

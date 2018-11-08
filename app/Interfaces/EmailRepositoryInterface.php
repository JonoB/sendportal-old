<?php

namespace App\Interfaces;

use App\Models\Email;

interface EmailRepositoryInterface extends BaseEloquentInterface
{
    public function queuedCampaigns();

    /**
     * Store an email linked against a polymorphic type
     *
     * @param string $morphType
     * @param int $morphId
     * @param array $data
     *
     * @return Email
     */
    public function storeMailable(string $morphType, int $morphId, array $data): Email;

    /**
     * Find an email ensuring it is associated with the given campaign
     *
     * @param int $campaignId
     * @param int $emailId
     * @param array $relations
     *
     * @return Email
     */
    public function findCampaignEmail(int $campaignId, int $emailId, array $relations = []): Email;
}

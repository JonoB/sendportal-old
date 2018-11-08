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
     * Find the email associated with the given campaign
     *
     * @param int $campaignId
     * @param array $relations
     *
     * @return Email
     */
    public function findCampaignEmail(int $campaignId, array $relations = []): Email;

    /**
     * Update a campaign's email
     *
     * @param int $campaignId
     * @param array $data
     *
     * @return Email
     */
    public function updateCampaignEmail(int $campaignId, array $data): Email;
}

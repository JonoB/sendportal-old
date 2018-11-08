<?php

namespace App\Interfaces;

use App\Models\Email;

interface EmailRepositoryInterface extends BaseEloquentInterface
{
    public function queuedCampaigns();
    public function storeMailable(string $morphType, int $morphId, array $data) : Email;
}

<?php

namespace App\Repositories;

use App\Interfaces\EmailRepositoryInterface;
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
}

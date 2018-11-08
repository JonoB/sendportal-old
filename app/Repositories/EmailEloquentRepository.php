<?php

namespace App\Repositories;

use App\Interfaces\EmailRepositoryInterface;
use App\Models\Automation;
use App\Models\CampaignStatus;
use App\Models\Email;

class EmailEloquentRepository extends BaseEloquentRepository implements EmailRepositoryInterface
{
    protected $modelName = Email::class;

    public function storeMailable(string $morphType, int $morphId, array $data) : Email
    {
        $data['mailable_type'] = $morphType;
        $data['mailable_id'] = $morphId;

        return $this->store($data);
    }

    public function queuedCampaigns()
    {
        return $this->getQueryBuilder()
            ->where('status_id', CampaignStatus::STATUS_QUEUED)
            ->where('mailable_type', 'App\Models\Campaign')
            ->with('segments')
            ->get();
    }
}

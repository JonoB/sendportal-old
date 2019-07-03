<?php

namespace App\Services;

use App\Interfaces\EmailWebhookServiceInterface;
use App\Models\Campaign;
use App\Models\CampaignLink;
use App\Models\UnsubscribeEventType;
use Carbon\Carbon;

class EmailWebhookService implements EmailWebhookServiceInterface
{

    /**
     * @param $messageId
     * @param $link
     * @return mixed
     */
    public function handleClick($messageId, $link)
    {
        $campaignSubscriber = \DB::table('campaign_subscriber')->where('message_id', $messageId)->first();
        \DB::table('campaign_subscriber')->where('message_id', $messageId)->increment('click_count');

        if ( ! $campaignSubscriber->click_count)
        {
            \DB::table('steps')
                ->where('mailable_id', $campaignSubscriber->campaign_id)
                ->where('mailable_type', Campaign::class)
                ->increment('click_count');
        }

        CampaignLink::updateOrCreate([
            'identifier' => md5($campaignSubscriber->campaign_id . '_' . $link),
        ], [
            'link' => $link,
            'campaign_id' => $campaignSubscriber->campaign_id,
            'click_count' => \DB::raw('click_count+1')
        ]);
    }

    /**
     * @param $messageId
     * @param Carbon $timestamp
     * @param $ipAddress
     */
    public function handleOpen($messageId, Carbon $timestamp, $ipAddress)
    {
        $campaignSubscriber = \DB::table('campaign_subscriber')->where('message_id', $messageId)->first();
        \DB::table('campaign_subscriber')->where('message_id', $messageId)->increment('open_count');

        if ( ! $campaignSubscriber->open_count)
        {
            \DB::table('steps')
                ->where('mailable_id', $campaignSubscriber->campaign_id)
                ->where('mailable_type', Campaign::class)
                ->increment('open_count');
        }

        \DB::table('campaign_subscriber')->where('message_id', $messageId)->whereNull('opened_at')->update([
            'opened_at' => $timestamp,
            'ip' => $ipAddress
        ]);
    }

    /**
     * @param $messageId
     * @param Carbon $timestamp
     */
    public function handleDelivery($messageId, Carbon $timestamp)
    {
        \DB::table('campaign_subscriber')->where('message_id', $messageId)->whereNull('delivered_at')->update([
            'delivered_at' => $timestamp
        ]);
    }

    /**
     * @param $messageId
     */
    public function handleComplaint($messageId)
    {
        return $this->unsubscribe($messageId, UnsubscribeEventType::COMPLAINT);
    }

    /**
     * @param $messageId
     */
    public function handlePermanentBounce($messageId)
    {
        return $this->unsubscribe($messageId, UnsubscribeEventType::BOUNCE);
    }

    /**
     * @param $messageId
     * @param $typeId
     */
    protected function unsubscribe($messageId, $typeId)
    {
        $subscriberId = \DB::table('campaign_subscriber')->where('message_id', $messageId)->value('subscriber_id');

        if ( ! $subscriberId)
        {
            return;
        }

        \DB::table('subscribers')->where('id', $subscriberId)->update([
            'unsubscribed_at' => now(),
            'unsubscribe_event_id' => $typeId,
            'updated_at' => now()
        ]);
    }
}
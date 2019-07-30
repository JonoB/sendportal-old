<?php

namespace App\Services;

use App\Interfaces\EmailWebhookServiceInterface;
use App\Models\AutomationSchedule;
use App\Models\AutomationStep;
use App\Models\Campaign;
use App\Models\CampaignLink;
use App\Models\MessageUrl;
use App\Models\UnsubscribeEventType;
use Carbon\Carbon;

class EmailWebhookService implements EmailWebhookServiceInterface
{

    // automation_step->click_count
    // automation_step->open_count
    // automation_step_urls->click_count
    /**
     * @param $messageId
     * @param Carbon $timestamp
     */
    public function handleDelivery($messageId, Carbon $timestamp)
    {
        \DB::table('messages')->where('message_id', $messageId)->whereNull('delivered_at')->update([
            'delivered_at' => $timestamp
        ]);
    }

    /**
     * @param $messageId
     * @param Carbon $timestamp
     * @param $ipAddress
     */
    public function handleOpen($messageId, Carbon $timestamp, $ipAddress)
    {
        \DB::table('messages')->where('message_id', $messageId)->whereNull('opened_at')->update([
            'opened_at' => $timestamp,
            'ip' => $ipAddress
        ]);

        $automationStep = $this->resolveAutomationStepFromMessage($messageId);

        \DB::table('automation_steps')->where('id', $automationStep->id)->increment('open_count');
    }

    /**
     * @param $messageId
     * @param $timestamp
     * @param $url
     * @return mixed
     */
    public function handleClick($messageId, Carbon $timestamp, $url)
    {
        \Log::info($messageId);

        \DB::table('messages')->where('message_id', $messageId)->whereNull('clicked_at')->update([
            'clicked_at' => $timestamp,
        ]);

        $automationStep = $this->resolveAutomationStepFromMessage($messageId);

        \DB::table('automation_steps')->where('id', $automationStep->id)->increment('click_count');

        $source = AutomationStep::class;
        $sourceId = $automationStep->id;

        MessageUrl::updateOrCreate([
            'hash' => md5($source . '_' . $sourceId . '_' . $url),
        ], [
            'source' => $source,
            'source_id' => $sourceId,
            'url' => $url,
            'click_count' => \DB::raw('click_count+1')
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
        $subscriberId = \DB::table('messages')->where('message_id', $messageId)->value('subscriber_id');

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

    protected function resolveAutomationStepFromMessage($messageId)
    {
        $message = \DB::table('messages')->where('message_id', $messageId)->first();

        if ($message->source != AutomationSchedule::class)
        {
            throw new \Exception('Unable to resolve source for message ID ' . $message->id);
        }

        $automationSchedule = \DB::table('automation_schedules')->where('id', $message->source_id)->first();

        return \DB::table('automation_steps')->where('id', $automationSchedule->automation_step_id)->first();
    }
}
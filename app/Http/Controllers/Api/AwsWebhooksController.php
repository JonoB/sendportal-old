<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class AwsWebhooksController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function handle()
    {
        $content = json_decode(request()->getContent(), true);

        if (array_get($content, 'Type') == 'SubscriptionConfirmation')
        {
            $subscribeUrl = array_get($content, 'SubscribeURL');

            $httpClient = new Client();
            $httpClient->get($subscribeUrl);

            \Log::info('subscribing', ['url' => $subscribeUrl]);

            return response('OK');
        }

        if ( ! array_get($content, 'Type') == 'Notification')
        {
            return response('OK (not processed).');
        }

        if ($event = json_decode(array_get($content, 'Message'), true))
        {
            return $this->processEmailEvent($event);
        }

        return response('OK (not processed).');
    }

    /**
     * @param array $event
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function processEmailEvent(array $event)
    {
        \Log::info($event);

        $messageId = array_get($event, 'mail.messageId');

        if ( ! $eventType = array_get($event, 'notificationType'))
        {
            return response('OK (not processed).');
        }

        $method = 'handle' . studly_case(str_slug($eventType, ''));

        \Log::info($method);

        // https://docs.aws.amazon.com/ses/latest/DeveloperGuide/event-publishing-retrieving-sns-examples.html#event-publishing-retrieving-sns-open
        // Bounce, Complaint, Delivery, Send Email, Reject Event, Open Event, Click Event
        if (method_exists($this, $method))
        {
            $this->{$method}($messageId, $event);

            return response('OK');
        }

        abort(404);
    }

    public function handleClick($messageId, array $event)
    {
        // https://docs.aws.amazon.com/ses/latest/DeveloperGuide/event-publishing-retrieving-sns-examples.html#event-publishing-retrieving-sns-click
        // https://docs.aws.amazon.com/ses/latest/DeveloperGuide/event-publishing-retrieving-sns-contents.html#event-publishing-retrieving-sns-contents-click-object
        \Log::info('click', $messageId);
    }

    public function handleOpen($messageId, array $event)
    {
        // https://docs.aws.amazon.com/ses/latest/DeveloperGuide/event-publishing-retrieving-sns-contents.html#event-publishing-retrieving-sns-contents-open-object
        // https://docs.aws.amazon.com/ses/latest/DeveloperGuide/event-publishing-retrieving-sns-examples.html#event-publishing-retrieving-sns-open

        \Log::info('open', $messageId);
    }

    public function handleReject($messageId, array $event)
    {
        // https://docs.aws.amazon.com/ses/latest/DeveloperGuide/event-publishing-retrieving-sns-contents.html#event-publishing-retrieving-sns-contents-reject-object
        // https://docs.aws.amazon.com/ses/latest/DeveloperGuide/event-publishing-retrieving-sns-examples.html#event-publishing-retrieving-sns-reject
        \Log::info('reject', $messageId);
    }

    /**
     * @param $messageId
     * @param array $event
     */
    protected function handleDelivery($messageId, array $event)
    {
        // https://docs.aws.amazon.com/ses/latest/DeveloperGuide/notification-contents.html#delivery-
        \Log::info('delivery', $messageId);
    }

    /**
     * @param $messageId
     * @param array $event
     */
    protected function handleComplaint($messageId, array $event)
    {
        // https://docs.aws.amazon.com/ses/latest/DeveloperGuide/notification-contents.html#complaint-object
        $complaint = array_get($event, 'complaint');
        $feedbackType = array_get($complaint, 'complaintFeedbackType');

        // abuse — Indicates unsolicited email or some other kind of email abuse.
        // auth-failure — Email authentication failure report.
        // fraud — Indicates some kind of fraud or phishing activity.
        // not-spam — Indicates that the entity providing the report does not consider the message to be spam. This may be used to correct a message that was incorrectly tagged or categorized as spam.
        // other — Indicates any other feedback that does not fit into other registered types.
        // virus — Reports that a virus is found in the originating message.
        \Log::info('complaint', $messageId);
    }

    /**
     * @param $messageId
     * @param array $event
     */
    protected function handleBounce($messageId, array $event)
    {
        // https://docs.aws.amazon.com/ses/latest/DeveloperGuide/notification-contents.html#bounce-object
        $bounce = array_get($event, 'bounce');
        $bounceType = array_get($bounce, 'bounceType');

        if (strtolower($bounceType) == 'permanent')
        {
            // unsubscribe
        }

        \Log::info('bounce', $messageId);
    }
}

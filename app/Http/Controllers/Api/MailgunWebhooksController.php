<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Interfaces\EmailWebhookServiceInterface;

class MailgunWebhooksController extends Controller
{
    /**
     * @var EmailWebhookServiceInterface
     */
    protected $emailWebhookService;

    /**
     * @param EmailWebhookServiceInterface $emailWebhookService
     */
    public function __construct(
        EmailWebhookServiceInterface $emailWebhookService
    )
    {
        $this->emailWebhookService = $emailWebhookService;
    }

    /**
     * Handle an incoming webhook.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function handle()
    {
        $content = json_decode(request()->getContent(), true);

        if ($event = array_get($content, 'event-data.event'))
        {
            return $this->processEmailEvent($content, $event);
        }

        return response('OK (not processed');
    }

    /**
     * Process an email event.
     *
     * @param array $content
     * @param string $event
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function processEmailEvent(array $content, string $event)
    {
        $messageId = $this->formatMessageId(array_get($content, 'event-data.message.headers.message-id'));

        $method = 'handle' . studly_case(str_slug($event, ''));

        if (method_exists($this, $method))
        {
            $this->{$method}($messageId, $content);

            return response('OK');
        }

        abort(404);
    }

    /**
     * Handle an email complained event.
     *
     * @param string $messageId
     * @param array $content
     */
    public function handleComplained(string $messageId, array $content)
    {
        $this->emailWebhookService->handleComplaint($messageId);
    }

    /**
     * Handle an email failed event.
     *
     * @param string $messageId
     * @param array $content
     */
    public function handleFailed(string $messageId, array $content)
    {
        $severity = array_get($content, 'event-data.severity');

        if($severity === 'permanent')
        {
            $this->emailWebhookService->handlePermanentBounce($messageId);
        }
    }

    /**
     * Handle an email delivery event.
     *
     * @param string $messageId
     * @param array $content
     */
    public function handleDelivered(string $messageId, array $content)
    {
        $timestamp = Carbon::createFromTimestamp(array_get($content, 'signature.timestamp'));

        $this->emailWebhookService->handleDelivery($messageId, $timestamp);
    }

    /**
     * Handle an email click event.
     *
     * @param string $messageId
     * @param array $content
     */
    public function handleClicked(string $messageId, array $content)
    {
        // NOTE(josh): Mailgun does not seem to allow us to track which specific link was clicked...
        // this may be problematic.
    }

    /**
     * Handle an email open event.
     *
     * @param string $messageId
     * @param array $content
     */
    public function handleOpened(string $messageId, array $content)
    {
        $ipAddress = array_get($content, 'event-data.ip');
        $timestamp = Carbon::createFromTimestamp(array_get($content, 'signature.timestamp'));

        $this->emailWebhookService->handleOpen($messageId, $timestamp, $ipAddress);
    }

    /**
     * Prepend/append crocodiles to message ID.
     *
     * @param string $messageId
     *
     * @return string
     */
    protected function formatMessageId(string $messageId)
    {
        return $messageId[0] == '<' ?
            $messageId :
            $messageId = '<' . $messageId . '>';
    }
}

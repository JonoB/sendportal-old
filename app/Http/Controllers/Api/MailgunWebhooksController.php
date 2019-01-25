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
        $messageId = array_get($content, 'event-data.message.headers.message-id');

        $method = 'handle' . studly_case(str_slug($event, ''));

        if (method_exists($this, $method))
        {
            $this->{$method}($messageId, $content);

            return response('OK');
        }

        abort(404);
    }

    /**
     * Handle an email open event.
     *
     * @param $messageId
     * @param array $content
     */
    public function handleOpen($messageId, array $content)
    {
        $ipAddress = array_get($content, 'event-data.ip');
        $timestamp = Carbon::parse(array_get($content, 'signature.timestamp'));

        $this->emailWebhookService->handleOpen($messageId, $timestamp, $ipAddress);
    }
}

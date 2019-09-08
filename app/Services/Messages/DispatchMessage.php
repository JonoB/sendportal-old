<?php

namespace App\Services\Messages;

use App\Factories\MailAdapterFactory;
use App\Models\AutomationSchedule;
use App\Models\Message;
use App\Repositories\AutomationScheduleEloquentRepository;
use App\Repositories\AutomationStepEloquentRepository;
use App\Repositories\MessageTenantRepository;

class DispatchMessage
{
    /**
     * @var MailAdapterFactory
     */
    protected $mailAdapter;

    /**
     * @var MessageTenantRepository
     */

    protected $messageRepo;

    /**
     * @var AutomationStepEloquentRepository
     */
    protected $automationScheduleRepo;

    /**
     * DispatchMessage constructor
     *
     * @param MailAdapterFactory $mailAdapter
     * @param MessageTenantRepository $messageRepo
     * @param AutomationScheduleEloquentRepository $automationScheduleRepo
     */
    public function __construct(
        MailAdapterFactory $mailAdapter,
        MessageTenantRepository $messageRepo,
        AutomationScheduleEloquentRepository $automationScheduleRepo
    )
    {
        $this->mailAdapter = $mailAdapter;
        $this->messageRepo = $messageRepo;
        $this->automationScheduleRepo = $automationScheduleRepo;
    }

    /**
     * Send the message
     *
     * @param Message $message
     * @param string $content
     * @return mixed
     * @throws \Exception
     */
    public function handle(Message $message, $content)
    {
        if ( ! $this->isValidMessage($message))
        {
            return false;
        }

        $this->dispatch($message, $content);
    }

    /**
     * Check that the message has not already been sent by getting
     * a fresh db record
     *
     * @param Message $message
     * @return bool
     * @throws \Exception
     */
    protected function isValidMessage(Message $message): bool
    {
        $message = $this->messageRepo->find(currentTeamId(), $message->id);

        return ! (bool)$message->sent_at;
    }

    /**
     * Dispatch the email via the given provider. Note that the $message->provider
     * is set on the model instance in a previous step of the pipeline
     *
     * @param Message $message
     * @param string $content
     * @return bool
     * @throws \Exception
     */
    protected function dispatch(Message $message, string $content): Message
    {
        $provider = $this->resolveProvider($message);

        $messageId = $this->mailAdapter->adapter($provider)
            ->send($message->from_email, $message->recipient_email, $message->subject, $content);

        return $this->markMessageAsSent($message, $messageId);
    }

    /**
     * Resolve the provider from the message
     *
     * @param Message $message
     * @return string
     * @throws \Exception
     */
    protected function resolveProvider(Message $message): string
    {
        if ($message->source != AutomationSchedule::class)
        {
            throw new \Exception('Unable to resolve source for message ID ' . $message->id);
        }

        if ( ! $automationSchedule = $this->automationScheduleRepo->find($message->source_id, ['automation_step.automation.provider.type']))
        {
            throw new \Exception('Unable to resolve automation schedule for message ID ' . $message->id);
        }

        if ( ! $provider = $automationSchedule->automation_step->automation->provider->name)
        {
            throw new \Exception('Unable to resolve provider for message ID ' . $message->id);
        }

        return strtolower(str_replace(' ', '', $automationSchedule->automation_step->automation->provider->type->name));
    }

    /**
     * Save the external message_id to the messages table
     *
     * @param Message $message
     * @param $messageId
     * @return Message
     */
    protected function markMessageAsSent(Message $message, $messageId): Message
    {
        return tap($message)->update([
            'message_id' => $messageId,
            'sent_at' => now(),
        ]);
    }
}

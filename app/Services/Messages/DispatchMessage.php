<?php

namespace App\Services\Messages;

use App\Factories\MailAdapterFactory;
use App\Models\AutomationStep;
use App\Models\Message;
use App\Repositories\AutomationStepEloquentRepository;
use App\Repositories\MessageEloquentRepository;

class DispatchMessage
{
    /**
     * @var MailAdapterFactory
     */
    protected $mailAdapter;

    /**
     * @var MessageEloquentRepository
     */

    protected $messageRepo;

    /**
     * @var AutomationStepEloquentRepository
     */
    protected $automationStepRepo;

    /**
     * DispatchMessage constructor
     *
     * @param MailAdapterFactory $mailAdapter
     * @param MessageEloquentRepository $messageRepo
     * @param AutomationStepEloquentRepository $automationStepRepo
     */
    public function __construct(
        MailAdapterFactory $mailAdapter,
        MessageEloquentRepository $messageRepo,
        AutomationStepEloquentRepository $automationStepRepo
    )
    {
        $this->mailAdapter = $mailAdapter;
        $this->messageRepo = $messageRepo;
        $this->automationStepRepo = $automationStepRepo;
    }

    /**
     * Send the message
     *
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        if ( ! $this->isValidMessage($message))
        {
            return false;
        }

        return $this->dispatch($message);
    }

    /**
     * Check that the message has not already been sent by getting
     * a fresh db record
     *
     * @param Message $message
     * @return bool
     */
    protected function isValidMessage(Message $message): bool
    {
        $message = $this->messageRepo->find($message->id);

        return ! (bool)$message->sent_at;
    }

    /**
     * Dispatch the email via the given provider. Note that the $message->provider
     * is set on the model instance in a previous step of the pipeline
     *
     * @param Message $message
     * @return bool
     */
    protected function dispatch(Message $message): bool
    {
        $messageId = $this->mailAdapter->adapter($message->provider)
            ->send($message->from_email, $message->recipient_email, $message->subject, $message->content);

        return $this->markMessageAsSent($message, $messageId);
    }

    /**
     * Execute the database query
     *
     * @param Message $message
     * @throws \Exception
     */
    protected function resolveProviderFromMessage(Message $message): void
    {
        if ($message->source == AutomationStep::class)
        {
            $automationStep = $this->automationStepRepo->find($message->source_id, ['automation.provider']);

            return strtolower(str_replace(' ', '', $automationStep->automation->provider->name));
        }

        throw new \Exception('Unable resolve provider for message ID ' . $message->id);
    }

    /**
     * Save the external message_id to the messages table
     *
     * @param Message $message
     * @param $messageId
     * @return bool
     */
    protected function markMessageAsSent(Message $message, $messageId)
    {
        $message->message_id = $messageId;
        $message->sent_at = now();

        return $message->save();
    }
}

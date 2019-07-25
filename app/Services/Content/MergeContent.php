<?php

namespace App\Services\Content;

use App\Models\AutomationSchedule;
use App\Models\Message;
use App\Models\Subscriber;
use App\Repositories\AutomationScheduleEloquentRepository;
use App\Repositories\AutomationStepEloquentRepository;

class MergeContent
{
    /**
     * @var string
     */
    protected $unsubscribeReplacementTag = '{{ unsubscribe_url }}';

    /**
     * @var AutomationStepEloquentRepository
     */
    protected $automationScheduleRepo;

    /**
     * MergeContent constructor
     *
     * @param AutomationScheduleEloquentRepository $automationScheduleRepo
     */
    public function __construct(AutomationScheduleEloquentRepository $automationScheduleRepo)
    {
        $this->automationScheduleRepo = $automationScheduleRepo;
    }

    /**
     * Get the content for the message
     *
     * @param Message $message
     * @return mixed
     * @throws \Exception
     */
    public function handle(Message $message)
    {
        return $this->resolveContent($message);
    }

    /**
     * Resolve the content
     *
     * @param Message $message
     * @return string
     * @throws \Exception
     */
    protected function resolveContent(Message $message): string
    {
        if ($message->source != AutomationSchedule::class)
        {
            throw new \Exception('Unable to resolve source for message ID ' . $message->id);
        }

        if ( ! $schedule = $this->automationScheduleRepo->find($message->source_id, ['automation_step']))
        {
            throw new \Exception('Unable to resolve automation step for message ID ' . $message->id);
        }

        if ( ! $content = $schedule->automation_step->content)
        {
            throw new \Exception('Unable to resolve content for automation step ' . $schedule->automation_step_id);
        }

        if ( ! $template = $schedule->automation_step->template)
        {
            throw new \Exception('Unable to resolve template for automation step ' . $schedule->automation_step_id);
        }

        $customContent = $this->mergeTags($content, $message->subscriber);

        return str_ireplace(['{{content}}', '{{ content }}'], $customContent, $template->content);
    }

    /**
     * Merge tags and links
     *
     * @param string $content
     * @param Subscriber $subscriber
     *
     * @return string
     */
    protected function mergeTags(string $content, Subscriber $subscriber): string
    {
        $content = $this->mergeSubscriberTags($content, $subscriber);

        return $this->mergeUnsubscribeLink($content, $subscriber);
    }

    /**
     * Merge tags for the subscriber
     *
     * @param string $content
     * @param Subscriber $subscriber
     *
     * @return string
     */
    protected function mergeSubscriberTags(string $content, Subscriber $subscriber): string
    {
        $tags = [
            'email' => $subscriber->email,
            'first_name' => $subscriber->first_name,
            'last_name' => $subscriber->last_name,
        ];

        // NOTE: regex doesn't seem to work here - I think it may be due to all the tags and inverted commas in html?
        foreach ($tags as $key => $value)
        {
            $content = normalize_tags($content, $key);

            $search = [
                '{{' . $key . '}}'
            ];

            $content = str_ireplace($search, $value, $content);
        }

        return $content;
    }

    /**
     * Merge in the unsubscribe link
     *
     * @param string $content
     * @param Subscriber $subscriber
     *
     * @return string
     */
    protected function mergeUnsubscribeLink(string $content, Subscriber $subscriber): string
    {
        $route = route('subscriptions.unsubscribe', $subscriber->hash);

        return str_ireplace($this->unsubscribeReplacementTag, $route, $content);
    }
}

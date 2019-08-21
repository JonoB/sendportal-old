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

        // merge template and custom content
        $mergedContent = str_ireplace(['{{content}}', '{{ content }}'], $content, $template->content);

        // merge tags into content
        return  $this->mergeTags($mergedContent, $message->subscriber);

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
        $content = $this->normaliseTags($content);

        $content = $this->mergeSubscriberTags($content, $subscriber);

        return $this->mergeUnsubscribeLink($content, $subscriber);
    }

    protected function normaliseTags($content)
    {
        $tags = [
            'email',
            'first_name',
            'last_name',
            'unsubscribe_url',
        ];

        // NOTE: regex doesn't seem to work here - I think it may be due to all the tags and inverted commas in html?
        foreach ($tags as $tag)
        {
            $content = normalize_tags($content, $tag);
        }

        return $content;
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

        foreach ($tags as $key => $replace)
        {
            $search =  '{{' . $key . '}}';

            $content = str_ireplace($search, $replace, $content);
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

        return str_ireplace(['{{ unsubscribe_url }}', '{{unsubscribe_url}}'], $route, $content);
    }
}

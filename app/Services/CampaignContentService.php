<?php

namespace App\Services;

use App\Interfaces\CampaignContentServiceInterface;
use App\Models\Campaign;
use App\Models\Subscriber;

class CampaignContentService implements CampaignContentServiceInterface
{
    /**
     * @var Campaign
     */
    protected $campaign;

    /**
     * @var string
     */
    protected $unsubscribeReplacementTag = '{{ unsubscribe_url }}';

    /**
     * Set the campaign and create base content ready for subscriber merging
     *
     * @param Campaign $campaign
     *
     * @return void
     */
    public function setCampaign(Campaign $campaign): void
    {
        $this->campaign = $campaign;
    }

    /**
     * Merge open tracking image and subscriber tags into the content
     *
     * @param Subscriber $subscriber
     *
     * @return string
     */
    public function getMergedContent(Subscriber $subscriber): string
    {
        return $this->mergeTags($this->campaign->content, $subscriber);
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

        // NOTE(mystery person): regex doesn't seem to work here - I think it may be due to all the tags and inverted commas in html?
        foreach ($tags as $key => $value)
        {
            $search = [
                '{{' . $key . '}}',
                '{{ ' . $key . ' }}',
                '{{' . $key . ' }}',
                '{{ ' . $key . '}}',
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
        $route = route('subscriptions.unsubscribe', $subscriber->id);

        return str_ireplace($this->unsubscribeReplacementTag, $route, $content);
    }
}

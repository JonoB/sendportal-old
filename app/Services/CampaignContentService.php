<?php

namespace App\Services;

use App\Interfaces\ContentUrlServiceInterface;
use App\Interfaces\GenerateOpenTrackingImageInterface;
use App\Interfaces\CampaignContentServiceInterface;
use App\Interfaces\CampaignUrlsRepositoryInterface;
use App\Models\Subscriber;
use App\Models\Campaign;

class CampaignContentService implements CampaignContentServiceInterface
{
    /**
     * @var Campaign
     */
    protected $campaign;

    /**
     * @var Subscriber
     */
    protected $subscriber;

    /**
     * Campaign content
     *
     * @var $content
     */
    protected $content;

    /**
     * @var GenerateOpenTrackingImageInterface
     */
    protected $openTrackingImageService;

    /**
     * @var ContentUrlServiceInterface
     */
    protected $contentUrlService;

    /**
     * @var CampaignUrlsRepositoryInterface
     */
    protected $campaignUrlsRepository;

    /**
     * Temporary tag that is replaced when the subscriber is merged in
     *
     * @var string
     */
    protected $subscriberIdReplacementTag = 'replace-this-with-subscriber-id';

    /**
     * Unsubscribe tag
     *
     * @var string
     */
    protected $unsubscribeReplacementTag = '{{ unsubscribe_url }}';

    /**
     * CampaignContentService constructor.
     *
     * @param GenerateOpenTrackingImageInterface $openTrackingImageService
     * @param ContentUrlServiceInterface $contentUrlsService
     * @param CampaignUrlsRepositoryInterface $campaignUrlsRepository
     */
    public function __construct(
        GenerateOpenTrackingImageInterface $openTrackingImageService,
        ContentUrlServiceInterface $contentUrlsService,
        CampaignUrlsRepositoryInterface $campaignUrlsRepository
    )
    {
        $this->openTrackingImageService = $openTrackingImageService;
        $this->contentUrlService = $contentUrlsService;
        $this->campaignUrlsRepository = $campaignUrlsRepository;
    }

    /**
     * Set the campaign and create base content
     * ready for subscriber merging
     *
     * @param Campaign $campaign
     * @return void
     */
    public function setCampaign(Campaign $campaign)
    {
        $this->campaign = $campaign;

        $content = $this->createBaseCampaignContent($campaign);

        $this->setContent($content);
    }

    /**
     * Merge open tracking image and subscriber tags into the content
     *
     * @param Subscriber $subscriber
     * @return string
     */
    public function getMergedContent(Subscriber $subscriber)
    {
        // embed open tracking image
        $content = $this->embedOpenTrackingImage($this->getContent(), $this->getCampaign()->id, $subscriber->id);

        // merge dynamic subscriber tags like email, name, unsubscribe link etc
        $content = $this->mergeTags($content, $subscriber);

        return $content;
    }

    /**
     * Replace all urls in a campaign for tracking
     *
     * @param Campaign $campaign
     * @return string
     */
    protected function createBaseCampaignContent(Campaign $campaign)
    {
        $content = $campaign->content;

        if ($this->trackClicks())
        {
            // get all the original urls from the campaign
            $originalUrls = $this->contentUrlService->extract($content);

            // for each of the original urls, store a record in the database
            // and return an array of replacement urls
            $replacementUrls = $this->generateReplacementUrls($campaign, $originalUrls);

            // substitute the original urls with the replacements
            $content = str_ireplace($originalUrls, $replacementUrls, $content);
        }

        return $content;
    }

    /**
     * Embed the open tracking image
     *
     * @param string $content
     * @param string $campaignId
     * @param string $subscriberId
     * @return string
     */
    protected function embedOpenTrackingImage($content, $campaignId, $subscriberId)
    {
        if ($this->trackOpens())
        {
            $image = $this->openTrackingImageService->generate($campaignId, $subscriberId);

            $content = str_replace('</body>', $image . '</body>', $content);
        }

        return $content;
    }

    protected function mergeTags($content, Subscriber $subscriber)
    {
        $content = $this->mergeSubscriberTags($content, $subscriber);

        return $this->mergeUnsubscribeLink($content, $subscriber);
    }

   /**
    * Merge tags for the subscriber
    *
    * @param string $content
    * @param Subscriber $subscriber
    * @return string
    */
    protected function mergeSubscriberTags($content, Subscriber $subscriber)
    {
        $tags = [
            'email' => $subscriber->email,
            'first_name' => $subscriber->first_name,
            'last_name' => $subscriber->last_name,
        ];

        // regex doesn't seem to work here - I think it
        // may be due to all the tags and inverted commas in html?
        /*foreach ($tags as $key => $value)
        {
            $pattern = '/{{\s?' . $key . '\s?}}/i';

            preg_replace($pattern, $value, $content);
        }*/

        foreach ($tags as $key => $value)
        {
            $search = [
                '{{' . $key . '}}',
                '{{ ' . $key . ' }}',
            ];
            $content = str_ireplace($search, $value, $content);
        }

        // merge subscriber into campaign url tracking
        return str_ireplace($this->subscriberIdReplacementTag, $subscriber->id, $content);
    }

    /**
     * Merge in the unsubscribe link
     *
     * @param string $content
     * @param Subscriber $subscriber
     * @return string
     */
    protected function mergeUnsubscribeLink($content, Subscriber $subscriber)
    {
        $route = route('subscriptions.unsubscribe', $subscriber->id);

        return str_ireplace($this->unsubscribeReplacementTag, $route, $content);
    }

    /**
     * Create an array of replacement urls for tracking clicks
     *
     * @param Campaign $campaign
     * @param array $originalUrls
     * @return array
     */
    protected function generateReplacementUrls($campaign, $originalUrls)
    {
        $replacementUrls = [];

        foreach ($originalUrls as $url)
        {
            $campaignUrl = $this->storeCampaignUrl($campaign->id, $url);

            // generate the replacement url, using a temporary tag for the subscriber. Once the subscriber
            // is merged in, then the tag will be replaced with the actual subscriber_id
            $replacementUrls[] = route('tracker.clicks', [$campaign->id, $this->subscriberIdReplacementTag, $campaignUrl->id]);
        }

        return $replacementUrls;
    }


    /**
     * Store a campaign tracking url in the database
     *
     * @param string $campaignId
     * @param string $link
     * @return mixed
     */
    protected function storeCampaignUrl($campaignId, $link)
    {
        return $this->campaignUrlsRepository->store([
            'campaign_id' => $campaignId,
            'original_url' => $link,
        ]);
    }

    /**
     * @return Campaign
     */
    protected function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * @return Subscriber
     */
    protected function getSubscriber()
    {
        return $this->subscriber;
    }

    /**
     * @return Subscriber
     */
    protected function getContent()
    {
        return $this->content;
    }

    /**
     * @return Subscriber
     */
    protected function setContent($content)
    {
        return $this->content = $content;
    }

    /**
     * Return if campaign is tracking opens
     *
     * @return bool
     */
    protected function trackOpens()
    {
        return (bool)$this->getCampaign()->track_opens;
    }

    /**
     * Return if campaign is tracking clicks
     *
     * @return bool
     */
    protected function trackClicks()
    {
        return (bool)$this->getCampaign()->track_clicks;
    }

}

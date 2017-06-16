<?php

namespace App\Services;

use App\Interfaces\ContentUrlServiceInterface;
use App\Interfaces\GenerateOpenTrackingImageInterface;
use App\Interfaces\NewsletterContentServiceInterface;
use App\Interfaces\NewsletterUrlsRepositoryInterface;
use App\Models\Subscriber;
use App\Models\Newsletter;

class NewsletterContentService implements NewsletterContentServiceInterface
{
    /**
     * @var Newsletter
     */
    protected $newsletter;

    /**
     * @var Subscriber
     */
    protected $subscriber;

    /**
     * Newsletter content
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
     * @var NewsletterUrlsRepositoryInterface
     */
    protected $newsletterUrlsRepository;

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
     * NewsletterContentService constructor.
     *
     * @param GenerateOpenTrackingImageInterface $openTrackingImageService
     * @param ContentUrlServiceInterface $contentUrlsService
     * @param NewsletterUrlsRepositoryInterface $newsletterUrlsRepository
     */
    public function __construct(
        GenerateOpenTrackingImageInterface $openTrackingImageService,
        ContentUrlServiceInterface $contentUrlsService,
        NewsletterUrlsRepositoryInterface $newsletterUrlsRepository
    )
    {
        $this->openTrackingImageService = $openTrackingImageService;
        $this->contentUrlService = $contentUrlsService;
        $this->newsletterUrlsRepository = $newsletterUrlsRepository;
    }

    /**
     * Set the newsletter and create base content
     * ready for subscriber merging
     *
     * @param Newsletter $newsletter
     * @return void
     */
    public function setNewsletter(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;

        $content = $this->createBaseNewsletterContent($newsletter);

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
        $content = $this->embedOpenTrackingImage($this->getContent(), $this->getNewsletter()->id, $subscriber->id);

        // merge dynamic subscriber tags like email, name, unsubscribe link etc
        $content = $this->mergeTags($content, $subscriber);

        return $content;
    }

    /**
     * Replace all urls in a newsletter for tracking
     *
     * @param Newsletter $newsletter
     * @return string
     */
    protected function createBaseNewsletterContent(Newsletter $newsletter)
    {
        $content = $newsletter->content;

        if ($this->trackClicks())
        {
            // get all the original urls from the newsletter
            $originalUrls = $this->contentUrlService->extract($content);

            // for each of the original urls, store a record in the database
            // and return an array of replacement urls
            $replacementUrls = $this->generateReplacementUrls($newsletter, $originalUrls);

            // substitute the original urls with the replacements
            $content = str_ireplace($originalUrls, $replacementUrls, $content);
        }

        return $content;
    }

    /**
     * Embed the open tracking image
     *
     * @param string $content
     * @param string $newsletterId
     * @param string $subscriberId
     * @return string
     */
    protected function embedOpenTrackingImage($content, $newsletterId, $subscriberId)
    {
        if ($this->trackOpens())
        {
            $image = $this->openTrackingImageService->generate($newsletterId, $subscriberId);

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
        ] + $this->processCustomFields($subscriber);

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

        // merge subscriber into newsletter url tracking
        return str_ireplace($this->subscriberIdReplacementTag, $subscriber->id, $content);
    }

    /**
     * Process the subscriber's custom fields into usable tags
     *
     * @param Subscriber $subscriber
     *
     * @return array
     */
    protected function processCustomFields(Subscriber $subscriber)
    {
        $fields = json_decode($subscriber->meta, true);

        $tags = [];

        if ( ! empty($fields))
        {
            foreach ($fields as $field)
            {
                $tags[$field['name']] = $field['value'];
            }
        }

        return $tags;
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
     * @param Newsletter $newsletter
     * @param array $originalUrls
     * @return array
     */
    protected function generateReplacementUrls($newsletter, $originalUrls)
    {
        $replacementUrls = [];

        foreach ($originalUrls as $url)
        {
            $newsletterUrl = $this->storeNewsletterUrl($newsletter->id, $url);

            // generate the replacement url, using a temporary tag for the subscriber. Once the subscriber
            // is merged in, then the tag will be replaced with the actual subscriber_id
            $replacementUrls[] = route('tracker.clicks', [$newsletter->id, $this->subscriberIdReplacementTag, $newsletterUrl->id]);
        }

        return $replacementUrls;
    }


    /**
     * Store a newsletter tracking url in the database
     *
     * @param string $newsletterId
     * @param string $link
     * @return mixed
     */
    protected function storeNewsletterUrl($newsletterId, $link)
    {
        return $this->newsletterUrlsRepository->store([
            'newsletter_id' => $newsletterId,
            'original_url' => $link,
        ]);
    }

    /**
     * @return Newsletter
     */
    protected function getNewsletter()
    {
        return $this->newsletter;
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
     * Return if newsletter is tracking opens
     *
     * @return bool
     */
    protected function trackOpens()
    {
        return (bool)$this->getNewsletter()->track_opens;
    }

    /**
     * Return if newsletter is tracking clicks
     *
     * @return bool
     */
    protected function trackClicks()
    {
        return (bool)$this->getNewsletter()->track_clicks;
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: jono
 * Date: 09/05/2017
 * Time: 22:30
 */

namespace App\Services;


use App\Interfaces\ContentUrlServiceInterface;
use App\Interfaces\GenerateOpenTrackingImageInterface;
use App\Interfaces\NewsletterUrlsRepositoryInterface;
use App\Models\Contact;
use App\Models\Newsletter;

class NewsletterContentService
{
    /**
     * @var Newsletter
     */
    protected $newsletter;

    /**
     * @var Contact
     */
    protected $contact;

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

    public function getMergedContent(Contact $contact)
    {

    }

    /**
     * Embed the open tracking image
     *
     * @param Newsletter $newsletter
     * @param Contact $contact
     * @return string
     */
    protected function embedOpenTrackingImage($newsletter, $contact)
    {
        $this->setContent($this->getNewsletter()->content);

        if ($this->trackOpens())
        {
            $image = $this->openTrackingImageService->generate($newsletter, $contact);

            return str_replace('</body>', $image . '</body>', $newsletter->content);
        }
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

            $replacementUrls[] = route('tracker.clicks', [$newsletter->id, 'replace-this-with-contact-id', $newsletterUrl->id]);
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
     * @param Newsletter $newsletter
     */
    public function setNewsletter(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;

        $this->setContent($this->createBaseNewsletterContent($newsletter));
    }

    /**
     * @return Contact
     */
    protected function getContact()
    {
        return $this->contact;
    }

    /**
     * @return Contact
     */
    protected function getContent()
    {
        return $this->content;
    }

    /**
     * @return Contact
     */
    protected function setContent($content)
    {
        return $this->content = $content;
    }

    protected function trackClicks()
    {
        return $this->getNewsletter()->track_clicks;
    }

    protected function trackOpens()
    {
        return $this->getNewsletter()->track_opens;
    }
}
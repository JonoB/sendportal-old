<?php

namespace App\Services;

use App\Interfaces\ContentUrlServiceInterface;
use App\Interfaces\GenerateOpenTrackingImageInterface;
use App\Interfaces\NewsletterContentServiceInterface;
use App\Interfaces\NewsletterUrlsRepositoryInterface;
use App\Models\Contact;
use App\Models\Newsletter;

class NewsletterContentService implements NewsletterContentServiceInterface
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
     * Temporary tag that is replaced when the contact is merged in
     *
     * @var string
     */
    protected $contactIdReplacementTag = 'replace-this-with-contact-id';

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
     * ready for contact merging
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
     * Merge open tracking image and contact tags into the content
     *
     * @param Contact $contact
     * @return string
     */
    public function getMergedContent(Contact $contact)
    {
        // embed open tracking image
        $content = $this->embedOpenTrackingImage($this->getContent(), $this->getNewsletter()->id, $contact->id);

        // merge dynamic contact tags like email, name, unsubscribe link etc
        $content = $this->mergeTags($content, $contact);

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
     * @param string $contactId
     * @return string
     */
    protected function embedOpenTrackingImage($content, $newsletterId, $contactId)
    {
        if ($this->trackOpens())
        {
            $image = $this->openTrackingImageService->generate($newsletterId, $contactId);

            $content = str_replace('</body>', $image . '</body>', $content);
        }

        return $content;
    }

    protected function mergeTags($content, Contact $contact)
    {
        $content = $this->mergeContactTags($content, $contact);

        return $this->mergeUnsubscribeLink($content, $contact);
    }

   /**
    * Merge tags for the contact
    *
    * @param string $content
    * @param Contact $contact
    * @return string
    */
    protected function mergeContactTags($content, Contact $contact)
    {
        $tags = [
            'Email' => $contact->email,
            'FirstName' => $contact->first_name,
            'LastName' => $contact->last_name,
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

        // merge contact into newsletter url tracking
        return str_ireplace($this->contactIdReplacementTag, $contact->id, $content);
    }

    protected function mergeUnsubscribeLink($content, Contact, $contact)
    {
        
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

            // generate the replacement url, using a temporary tag for the contact. Once the contact
            // is merged in, then the tag will be replaced with the actual contact_id
            $replacementUrls[] = route('tracker.clicks', [$newsletter->id, $this->contactIdReplacementTag, $newsletterUrl->id]);
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

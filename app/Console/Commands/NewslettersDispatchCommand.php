<?php

namespace App\Console\Commands;

use App\Interfaces\ContactRepositoryInterface;
use App\Interfaces\ContentUrlServiceInterface;
use App\Interfaces\NewsletterDispatchInterface;
use App\Interfaces\NewsletterUrlsRepositoryInterface;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Models\Newsletter;
use App\Models\NewsletterStatus;
use App\Models\Segment;
use App\Services\NewsletterDispatchService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class NewslettersDispatchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sp:newsletters:dispatch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch all newsletters waiting in the queue';

    /**
     * @var ContactRepositoryInterface
     */
    protected $contactRepo;

    /**
     * @var NewsletterRepositoryInterface
     */
    protected $newsletterRepo;

    /**
     * @var NewsletterUrlsRepositoryInterface
     */
    protected $newsletterUrlsRepo;

    /**
     * @var NewsletterDispatchService
     */
    protected $newsletterDispatchService;

    /**
     * @var ContentUrlServiceInterface
     */
    protected $contentUrlService;

    /**
     * Store sent items for this newsletter so
     * that we don't send to the same person
     * more than once
     *
     * @var array
     */
    protected $sentItems = [];

    /**
     * NewslettersDispatchCommand constructor.
     */
    public function __construct(
        ContactRepositoryInterface $contactRepository,
        NewsletterRepositoryInterface $newsletterRepository,
        NewsletterUrlsRepositoryInterface $newsletterUrlsRepository,
        NewsletterDispatchInterface $newsletterDispatchService,
        ContentUrlServiceInterface $contentUrlsService
    )
    {
        parent::__construct();

        $this->contactRepo = $contactRepository;
        $this->newsletterRepo = $newsletterRepository;
        $this->newsletterUrlsRepo = $newsletterUrlsRepository;
        $this->newsletterDispatchService = $newsletterDispatchService;
        $this->contentUrlService = $contentUrlsService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed|void
     */
    public function handle()
    {
        $newsletters = $this->getQueuedNewsletters();

        if ( ! $newsletters)
        {
            $this->info('No queued newsletters; nothing more to do here');
            return;
        }

        $this->info('Number of newsletters in queued status: ' . count($newsletters));

        foreach ($newsletters as $newsletter)
        {
            $this->handleNewsletter($newsletter);
        }
    }

    /**
     * Handle a single newsletter
     *
     * @param Newsletter $newsletter
     */
    protected function handleNewsletter(Newsletter $newsletter)
    {
        $this->info('Handling Newsletter ID:' . $newsletter->id . ' (' . $newsletter->name . ')');

        if ( ! $this->checkNewsletterStatus($newsletter->id))
        {
            $this->error('Newsletter status is not queued, skipping');

            return;
        }

        $this->markNewsletterAsSending($newsletter->id);

        if ($newsletter->track_clicks)
        {
            $newsletter->content = $this->replaceUrls($newsletter);
        }

        foreach ($newsletter->segments as $segment)
        {
            $this->handleSegment($newsletter, $segment);
        }

        $this->markNewsletterAsSent($newsletter->id);
    }

    /**
     * Handle a segment from a newsletter
     *
     * @param Newsletter $newsletter
     * @param Segment $segment
     */
    protected function handleSegment(Newsletter $newsletter, Segment $segment)
    {
        $this->info('-Handling Newsletter Segment ID:' . $segment->id . ' (' . $segment->name . ')');

        $contacts = $this->getSegmentContacts($segment);

        foreach ($contacts as $contact)
        {
            if ( ! $this->canSentToContact($newsletter->id, $contact->id))
            {
                $this->info('--Skipping Contact ID:' . $contact->id . ' (' . $contact->email . ')');

                continue;
            }

            $this->info('--Handling Contact ID:' . $contact->id . ' (' . $contact->email . ')');

            $this->newsletterDispatchService->send($newsletter, $contact);
        }
    }

    /**
     * Get all queued newsletters
     *
     * @return mixed
     */
    protected function getQueuedNewsletters()
    {
        return $this->newsletterRepo->getBy('status_id', NewsletterStatus::STATUS_QUEUED, ['segments']);
    }

    /**
     * Load contacts for a single segment
     * @todo this needs to be improved so that we chunk items
     *
     * @param $segment
     * @return Collection
     */
    protected function getSegmentContacts(Segment $segment)
    {
        $segment->load('contacts');

        return $segment->contacts;
    }

    /**
     * Replace all urls in a newsletter for tracking
     *
     * @param Newsletter $newsletter
     * @return string
     */
    protected function replaceUrls(Newsletter $newsletter)
    {
        $originalUrls = $this->contentUrlService->extract($newsletter->content);

        $replaceUrls = [];

        foreach ($originalUrls as $url)
        {
            $newsletterUrl = $this->storeNewsletterUrl($newsletter->id, $url);

            $replaceUrls[] = route('tracker.clicks', [$newsletter->id, 'replace-this-with-contact-id', $newsletterUrl->id]);
        }

        return $this->contentUrlService->replaceUrls($newsletter->content, $originalUrls, $replaceUrls);
    }


    /**
     * Check that the status of the newsletter is still queued
     *
     * @param int $newsletterId
     * @return bool
     */
    protected function checkNewsletterStatus($newsletterId)
    {
        $newsletter = $this->newsletterRepo->find($newsletterId);

        return $newsletter->status_id == NewsletterStatus::STATUS_QUEUED;
    }

    /**
     * Check if we can send to this contact
     * @todo check how this would impact on memory with 200k contacts?
     *
     * @param int $newsletterId
     * @param int $contactId
     * @return bool
     */
    protected function canSentToContact($newsletterId, $contactId)
    {
        $key = $newsletterId . ':' . $contactId;

        if (in_array($key, $this->getSentItems()))
        {
            return false;
        }

        $this->appendSentItem($key);

        return true;
    }

    /**
     * Append a value to the sentItems
     *
     * @param $value
     */
    protected function appendSentItem($value)
    {
        $this->sentItems[] = $value;
    }

    /**
     * Get all sentItems
     *
     * @return array
     */
    protected function getSentItems()
    {
        return $this->sentItems;
    }

    /**
     * Update newsletter status to sending
     *
     * @param $newsletterId
     */
    protected function markNewsletterAsSending($newsletterId)
    {
        $this->newsletterRepo->update($newsletterId, [
            'status_id' => NewsletterStatus::STATUS_SENDING
        ]);
    }

    /**
     * Update newsletter status to sent
     *
     * @param $newsletterId
     */
    protected function markNewsletterAsSent($newsletterId)
    {
        $this->newsletterRepo->update($newsletterId, [
            'status_id' => NewsletterStatus::STATUS_SENT,
            'sent_count' => count($this->getSentItems())
        ]);
    }
}

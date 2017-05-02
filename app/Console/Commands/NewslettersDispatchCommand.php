<?php

namespace App\Console\Commands;

use App\Interfaces\ContactRepositoryInterface;
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
     * @var NewsletterDispatchService
     */
    protected $newsletterDispatchService;

    /**
     * NewslettersDispatchCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->contactRepo = app()->make(ContactRepositoryInterface::class);
        $this->newsletterRepo = app()->make(NewsletterRepositoryInterface::class);
        $this->newsletterDispatchService = app()->make(NewsletterDispatchService::class);
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
        $this->info('Handing Newsletter:' . $newsletter->name);

        if ( ! $this->checkNewsletterStatus($newsletter->id))
        {
            $this->error('Newsletter status is not queued, skipping');

            return;
        }

        $this->markNewsletterAsSending($newsletter->id);

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
        $this->info('-Handing Newsletter Segment:' . $segment->name);

        $contacts = $this->getSegmentContacts($segment);

        foreach ($contacts as $contact)
        {
            //@todo - here we should check that we haven't already
            // sent to this contact. For example, the same contact
            // could be in more than one segment and we want to
            // prevent that
            $this->info('--Handing Contact:' . $contact->email);

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
            'status_id' => NewsletterStatus::STATUS_SENT
        ]);
    }
}

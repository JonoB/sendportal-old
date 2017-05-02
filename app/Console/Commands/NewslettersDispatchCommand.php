<?php

namespace App\Console\Commands;

use App\Interfaces\NewsletterRepositoryInterface;
use App\Models\Newsletter;
use App\Models\NewsletterStatus;
use App\Models\Segment;
use App\Services\NewsletterDispatchService;
use Illuminate\Console\Command;

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

    protected $newsletterRepo;
    protected $newsletterDispatchService;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->newsletterRepo = app()->make(NewsletterRepositoryInterface::class);
        $this->newsletterDispatchService = app()->make(NewsletterDispatchService::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
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
        $this->info('Handing Newsletter Segment:' . $segment->name . ' with ' . count($segment->contacts) . ' contacts');

        foreach ($segment->contacts as $contact)
        {
            $this->info('Handing Contact:' . $contact->email);

            $this->newsletterDispatchService->send($newsletter, $contact);
        }
    }

    protected function getQueuedNewsletters()
    {
        return $this->newsletterRepo->getBy('status_id', NewsletterStatus::STATUS_QUEUED, ['segments']);
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

    protected function markNewsletterAsSending($newsletterId)
    {
        $this->newsletterRepo->update($newsletterId, [
            'status_id' => NewsletterStatus::STATUS_SENDING
        ]);
    }

    protected function markNewsletterAsSent($newsletterId)
    {
        $this->newsletterRepo->update($newsletterId, [
            'status_id' => NewsletterStatus::STATUS_SENT
        ]);
    }
}

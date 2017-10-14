<?php

namespace App\Console\Commands;

use App\Interfaces\NewsletterSubscriberRepositoryInterface;
use App\Interfaces\SubscriberRepositoryInterface;
use App\Interfaces\ContentUrlServiceInterface;
use App\Interfaces\NewsletterContentServiceInterface;
use App\Interfaces\NewsletterDispatchInterface;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Models\Subscriber;
use App\Models\Newsletter;
use App\Models\NewsletterStatus;
use App\Models\SubscriberList;
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
     * @var SubscriberRepositoryInterface
     */
    protected $subscriberRepo;

    /**
     * @var NewsletterRepositoryInterface
     */
    protected $newsletterRepo;

    /**
     * @var NewsletterDispatchService
     */
    protected $newsletterDispatchService;

    /**
     * @var ContentUrlServiceInterface
     */
    protected $newsletterContentService;

    /**
     * @var NewsletterSubscriberRepositoryInterface
     */
    protected $newsletterSubscriberRepository;

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
        NewsletterSubscriberRepositoryInterface $newsletterSubscriberRepository,
        SubscriberRepositoryInterface $subscriberRepository,
        NewsletterRepositoryInterface $newsletterRepository,
        NewsletterDispatchInterface $newsletterDispatchService,
        NewsletterContentServiceInterface $newsletterContentService
    )
    {
        parent::__construct();

        $this->newsletterSubscriberRepository = $newsletterSubscriberRepository;
        $this->subscriberRepo = $subscriberRepository;
        $this->newsletterRepo = $newsletterRepository;
        $this->newsletterDispatchService = $newsletterDispatchService;
        $this->newsletterContentService = $newsletterContentService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed|void
     */
    public function handle()
    {
        if ( ! $newsletters = $this->getQueuedNewsletters())
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

        $this->newsletterContentService->setNewsletter($newsletter);

        foreach ($newsletter->lists as $list)
        {
            $this->handleList($newsletter, $list);
        }

        $this->markNewsletterAsSent($newsletter->id);
    }

    /**
     * Handle a tag from a newsletter
     *
     * @param Newsletter $newsletter
     * @param SubscriberList $list
     */
    protected function handleList(Newsletter $newsletter, SubscriberList $list)
    {
        $this->info('-Handling Newsletter SubscriberList ID:' . $list->id . ' (' . $list->name . ')');

        $subscribers = $this->getActiveListSubscribers($list);

        $this->info('-Number of subscribers in this list:' . count($subscribers));

        foreach ($subscribers as $subscriber)
        {
            if ( ! $this->canSendToSubscriber($newsletter->id, $subscriber->id))
            {
                $this->info('--Skipping Subscriber ID:' . $subscriber->id . ' (' . $subscriber->email . ')');

                continue;
            }

            $this->info('--Handling Subscriber ID:' . $subscriber->id . ' (' . $subscriber->email . ')');

            $content = $this->newsletterContentService->getMergedContent($subscriber);

            if ($this->newsletterDispatchService->send($newsletter->from_email, $subscriber->email, $newsletter->subject, $content))
            {
                $this->createDatabaseRecord($newsletter, $subscriber);
            }
        }
    }

    /**
     * Create tracking record
     *
     * @param Newsletter $newsletter
     * @param Subscriber $subscriber
     * @return void
     */
    protected function createDatabaseRecord(Newsletter $newsletter, Subscriber $subscriber)
    {
        $this->newsletterSubscriberRepository->store([
            'newsletter_id' => $newsletter->id,
            'subscriber_id' => $subscriber->id,
        ]);
    }

    /**
     * Get all queued newsletters
     *
     * @return mixed
     */
    protected function getQueuedNewsletters()
    {
        return $this->newsletterRepo->getBy('status_id', NewsletterStatus::STATUS_QUEUED, ['lists']);
    }

    /**
     * Load active subscribers for a single list
     * @todo this needs to be improved so that we chunk items
     *
     * @param $list
     * @return Collection
     */
    protected function getActiveListSubscribers(SubscriberList $list)
    {
        $list->load('active_subscribers');

        return $list->active_subscribers;
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
     * Check if we can send to this subscriber
     * @todo check how this would impact on memory with 200k subscribers?
     *
     * @param int $newsletterId
     * @param int $subscriberId
     * @return bool
     */
    protected function canSendToSubscriber($newsletterId, $subscriberId)
    {
        $key = $newsletterId . ':' . $subscriberId;

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

<?php

namespace App\Console\Commands;

use App\Interfaces\CampaignSubscriberRepositoryInterface;
use App\Interfaces\SubscriberRepositoryInterface;
use App\Interfaces\ContentUrlServiceInterface;
use App\Interfaces\CampaignContentServiceInterface;
use App\Interfaces\CampaignDispatchInterface;
use App\Interfaces\CampaignRepositoryInterface;
use App\Models\Segment;
use App\Models\Subscriber;
use App\Models\Campaign;
use App\Models\CampaignStatus;
use App\Services\CampaignDispatchService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class CampaignDispatchCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'sp:campaigns:dispatch';

    /**
     * @var string
     */
    protected $description = 'Dispatch all campaigns waiting in the queue';

    /**
     * @var SubscriberRepositoryInterface
     */
    protected $subscriberRepo;

    /**
     * @var CampaignRepositoryInterface
     */
    protected $campaignRepo;

    /**
     * @var CampaignDispatchService
     */
    protected $campaignDispatchService;

    /**
     * @var ContentUrlServiceInterface
     */
    protected $campaignContentService;

    /**
     * @var CampaignSubscriberRepositoryInterface
     */
    protected $campaignSubscriberRepository;

    /**
     * Store sent items for this campaign so that we don't send to the same person more than once
     *
     * @var array
     */
    protected $sentItems = [];

    /**
     * @param CampaignSubscriberRepositoryInterface $campaignSubscriberRepository
     * @param SubscriberRepositoryInterface $subscriberRepository
     * @param CampaignRepositoryInterface $campaignRepository
     * @param CampaignDispatchInterface $campaignDispatchService
     * @param CampaignContentServiceInterface $campaignContentService
     */
    public function __construct(
        CampaignSubscriberRepositoryInterface $campaignSubscriberRepository,
        SubscriberRepositoryInterface $subscriberRepository,
        CampaignRepositoryInterface $campaignRepository,
        CampaignDispatchInterface $campaignDispatchService,
        CampaignContentServiceInterface $campaignContentService
    )
    {
        parent::__construct();

        $this->campaignSubscriberRepository = $campaignSubscriberRepository;
        $this->subscriberRepo = $subscriberRepository;
        $this->campaignRepo = $campaignRepository;
        $this->campaignDispatchService = $campaignDispatchService;
        $this->campaignContentService = $campaignContentService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed|void
     */
    public function handle()
    {
        if ( ! $campaigns = $this->getQueuedCampaigns())
        {
            $this->line('No queued campaigns; nothing more to do here');

            return;
        }

        $this->info('Number of campaigns in queued status: ' . \count($campaigns));

        foreach ($campaigns as $campaign)
        {
            $this->handleCampaign($campaign);
        }
    }

    /**
     * Handle a single campaign
     *
     * @param Campaign $campaign
     *
     * @return void
     */
    protected function handleCampaign(Campaign $campaign): void
    {
        $this->info("Handling Campaign ID: {$campaign->id} ({$campaign->name})");

        if ( ! $this->checkCampaignStatus($campaign->id))
        {
            $this->error('Campaign status is not queued, skipping');

            return;
        }

        $this->markCampaignAsSending($campaign->id);

        $this->campaignContentService->setCampaign($campaign);

        foreach ($campaign->segments as $segment)
        {
            $this->handleSegment($campaign, $segment);
        }

        $this->markCampaignAsSent($campaign->id);
    }

    /**
     * Handle a tag from a campaign
     *
     * @param Campaign $campaign
     * @param Segment $segment
     *
     * @return void
     */
    protected function handleSegment(Campaign $campaign, Segment $segment): void
    {
        $this->line("- Handling Campaign Segment ID: {$segment->id} ({$segment->name})");

        $subscribers = $this->getActiveSegmentSubscribers($segment);

        $this->line('- Number of subscribers in this segment: ' . \count($subscribers));

        foreach ($subscribers as $subscriber)
        {
            if ( ! $this->canSendToSubscriber($campaign->id, $subscriber->id))
            {
                $this->info("-- Skipping Subscriber ID: {$subscriber->id} ({$subscriber->email})");

                continue;
            }

            $this->info("-- Handling Subscriber ID: {$subscriber->id} ({$subscriber->email})");

            $this->dispatch($campaign, $subscriber, $this->campaignContentService->getMergedContent($subscriber));
        }
    }

    /**
     * Dispatch the campaign email
     *
     * @param Campaign $campaign
     * @param Subscriber $subscriber
     * @param string $content
     *
     * @return void
     */
    protected function dispatch(Campaign $campaign, Subscriber $subscriber, string $content): void
    {
        $messageId = $this->campaignDispatchService->send(
            'ses',
            $campaign->from_email,
            $subscriber->email,
            $campaign->subject,
            $content
        );

        if ($messageId)
        {
            $this->createDatabaseRecord($campaign, $subscriber, $messageId);
        }
        else
        {
            $this->comment('-- No message ID was returned for us to track.');
        }
    }

    /**
     * Create tracking record
     *
     * @param Campaign $campaign
     * @param Subscriber $subscriber
     * @param string $messageId
     *
     * @return void
     */
    protected function createDatabaseRecord(Campaign $campaign, Subscriber $subscriber, string $messageId): void
    {
        $this->campaignSubscriberRepository->store([
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
            'message_id' => $messageId,
        ]);
    }

    /**
     * Get all queued campaigns
     *
     * @return EloquentCollection
     */
    protected function getQueuedCampaigns(): EloquentCollection
    {
        return $this->campaignRepo->getBy('status_id', CampaignStatus::STATUS_QUEUED, ['segments']);
    }

    /**
     * Load active subscribers for a single segment
     *
     * @todo this needs to be improved so that we chunk items
     *
     * @param Segment $segment
     *
     * @return Collection
     */
    protected function getActiveSegmentSubscribers(Segment $segment): Collection
    {
        $segment->load('active_subscribers');

        return $segment->active_subscribers;
    }

    /**
     * Check that the status of the campaign is still queued
     *
     * @param int $campaignId
     *
     * @return bool
     */
    protected function checkCampaignStatus(int $campaignId): bool
    {
        return $this->campaignRepo->find($campaignId)->status_id === CampaignStatus::STATUS_QUEUED;
    }

    /**
     * Check if we can send to this subscriber
     * @todo check how this would impact on memory with 200k subscribers?
     *
     * @param int $campaignId
     * @param int $subscriberId
     *
     * @return bool
     */
    protected function canSendToSubscriber(int $campaignId, int $subscriberId): bool
    {
        $key = "{$campaignId}:{$subscriberId}";

        if (\in_array($key, $this->getSentItems(), true))
        {
            return false;
        }

        $this->appendSentItem($key);

        return true;
    }

    /**
     * Append a value to the sentItems
     *
     * @param string $value
     *
     * @return void
     */
    protected function appendSentItem(string $value): void
    {
        $this->sentItems[] = $value;
    }

    /**
     * Get all sentItems
     *
     * @return array
     */
    protected function getSentItems(): array
    {
        return $this->sentItems;
    }

    /**
     * Update campaign status to sending
     *
     * @param $campaignId
     *
     * @return void
     */
    protected function markCampaignAsSending($campaignId): void
    {
        $this->campaignRepo->update($campaignId, [
            'status_id' => CampaignStatus::STATUS_SENDING
        ]);
    }

    /**
     * Update campaign status to sent
     *
     * @param $campaignId
     *
     * @return void
     */
    protected function markCampaignAsSent($campaignId): void
    {
        $this->campaignRepo->update($campaignId, [
            'status_id' => CampaignStatus::STATUS_SENT,
            'sent_count' => \count($this->getSentItems())
        ]);
    }
}

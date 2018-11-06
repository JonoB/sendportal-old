<?php

namespace App\Console\Commands;

use App\Interfaces\CampaignSubscriberRepositoryInterface;
use App\Interfaces\SubscriberRepositoryInterface;
use App\Interfaces\ContentUrlServiceInterface;
use App\Interfaces\CampaignContentServiceInterface;
use App\Interfaces\CampaignDispatchInterface;
use App\Interfaces\CampaignRepositoryInterface;
use App\Models\Subscriber;
use App\Models\Campaign;
use App\Models\CampaignStatus;
use App\Models\SubscriberList;
use App\Services\CampaignDispatchService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class CampaignDispatchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sp:campaigns:dispatch';

    /**
     * The console command description.
     *
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
     * Store sent items for this campaign so
     * that we don't send to the same person
     * more than once
     *
     * @var array
     */
    protected $sentItems = [];

    /**
     * CampaignsDispatchCommand constructor.
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
            $this->info('No queued campaigns; nothing more to do here');

            return;
        }

        $this->info('Number of campaigns in queued status: ' . count($campaigns));

        foreach ($campaigns as $campaign)
        {
            $this->handleCampaign($campaign);
        }
    }

    /**
     * Handle a single campaign
     *
     * @param Campaign $campaign
     */
    protected function handleCampaign(Campaign $campaign)
    {
        $this->info('Handling Campaign ID:' . $campaign->id . ' (' . $campaign->name . ')');

        if ( ! $this->checkCampaignStatus($campaign->id))
        {
            $this->error('Campaign status is not queued, skipping');

            return;
        }

        $this->markCampaignAsSending($campaign->id);

        $this->campaignContentService->setCampaign($campaign);

        foreach ($campaign->lists as $list)
        {
            $this->handleList($campaign, $list);
        }

        $this->markCampaignAsSent($campaign->id);
    }

    /**
     * Handle a tag from a campaign
     *
     * @param Campaign $campaign
     * @param SubscriberList $list
     */
    protected function handleList(Campaign $campaign, SubscriberList $list)
    {
        $this->info('-Handling Campaign SubscriberList ID:' . $list->id . ' (' . $list->name . ')');

        $subscribers = $this->getActiveListSubscribers($list);

        $this->info('-Number of subscribers in this list:' . count($subscribers));

        foreach ($subscribers as $subscriber)
        {
            if ( ! $this->canSendToSubscriber($campaign->id, $subscriber->id))
            {
                $this->info('--Skipping Subscriber ID:' . $subscriber->id . ' (' . $subscriber->email . ')');

                continue;
            }

            $this->info('--Handling Subscriber ID:' . $subscriber->id . ' (' . $subscriber->email . ')');

            $content = $this->campaignContentService->getMergedContent($subscriber);

            if ($this->campaignDispatchService->send($campaign->from_email, $subscriber->email, $campaign->subject, $content))
            {
                $this->createDatabaseRecord($campaign, $subscriber);
            }
        }
    }

    /**
     * Create tracking record
     *
     * @param Campaign $campaign
     * @param Subscriber $subscriber
     * @return void
     */
    protected function createDatabaseRecord(Campaign $campaign, Subscriber $subscriber)
    {
        $this->campaignSubscriberRepository->store([
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
        ]);
    }

    /**
     * Get all queued campaigns
     *
     * @return mixed
     */
    protected function getQueuedCampaigns()
    {
        return $this->campaignRepo->getBy('status_id', CampaignStatus::STATUS_QUEUED, ['lists']);
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
     * Check that the status of the campaign is still queued
     *
     * @param int $campaignId
     * @return bool
     */
    protected function checkCampaignStatus($campaignId)
    {
        $campaign = $this->campaignRepo->find($campaignId);

        return $campaign->status_id == CampaignStatus::STATUS_QUEUED;
    }

    /**
     * Check if we can send to this subscriber
     * @todo check how this would impact on memory with 200k subscribers?
     *
     * @param int $campaignId
     * @param int $subscriberId
     * @return bool
     */
    protected function canSendToSubscriber($campaignId, $subscriberId)
    {
        $key = $campaignId . ':' . $subscriberId;

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
     * Update campaign status to sending
     *
     * @param $campaignId
     */
    protected function markCampaignAsSending($campaignId)
    {
        $this->campaignRepo->update($campaignId, [
            'status_id' => CampaignStatus::STATUS_SENDING
        ]);
    }

    /**
     * Update campaign status to sent
     *
     * @param $campaignId
     */
    protected function markCampaignAsSent($campaignId)
    {
        $this->campaignRepo->update($campaignId, [
            'status_id' => CampaignStatus::STATUS_SENT,
            'sent_count' => count($this->getSentItems())
        ]);
    }
}
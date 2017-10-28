<?php

namespace App\Http\Controllers;

use App\Interfaces\CampaignSubscriberRepositoryInterface;
use App\Interfaces\SubscriberRepositoryInterface;
use App\Interfaces\CampaignRepositoryInterface;
use App\Interfaces\CampaignUrlsRepositoryInterface;
use App\Repositories\CampaignUrlsEloquentRepository;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    /**
     * @var SubscriberRepositoryInterface
     */
    protected $subscriberRepository;

    /**
     * UnsubscribeController constructor.
     *
     * @param SubscriberRepositoryInterface $subscriberRepository
     */
    public function __construct(
        SubscriberRepositoryInterface $subscriberRepository
    )
    {
        $this->subscriberRepository = $subscriberRepository;
    }

    /**
     * Track email opens
     *
     * @param Request $request
     * @param string $subscriberId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function unsubscribe(Request $request, $subscriberId)
    {
        if ( ! $this->subscriberRepository->find($subscriberId))
        {
            abort(404);
        }

        return view('subscriptions.unsubscribe', compact('subscriberId'));
    }

    public function update(Request $request)
    {
        $subscriberId = $request->get('subscriber_id');

        if ( ! $this->subscriberRepository->find($subscriberId))
        {
            abort(404);
        }

        $unsubscribed = $request->get('unsubscribed');

        $this->subscriberRepository->update($subscriberId, [
            'unsubscribed' => $unsubscribed,
        ]);

        if ($unsubscribed)
        {
            return redirect()->route('subscriptions.subscribe', $subscriberId);
        }

        return redirect()->route('subscriptions.unsubscribe', $subscriberId);
    }

    public function subscribe(Request $request, $subscriberId)
    {
        if ( ! $this->subscriberRepository->find($subscriberId))
        {
            abort(404);
        }

        return view('subscriptions.subscribe', compact('subscriberId'));
    }

 }

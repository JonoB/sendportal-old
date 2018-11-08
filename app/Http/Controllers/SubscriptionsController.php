<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionToggleRequest;
use App\Interfaces\SubscriberRepositoryInterface;
use App\Models\UnsubscribeEventType;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionsController extends Controller
{
    /**
     * @var SubscriberRepositoryInterface
     */
    protected $subscribers;

    /**
     * @param SubscriberRepositoryInterface $subscribers
     */
    public function __construct(SubscriberRepositoryInterface $subscribers)
    {
        $this->subscribers = $subscribers;
    }

    /**
     * Unsubscribe a subscriber
     *
     * @param string $subscriberHash
     *
     * @return View
     */
    public function unsubscribe($subscriberHash)
    {
        $subscriber = $this->subscribers->findBy('hash', $subscriberHash);

        return view('subscriptions.unsubscribe', compact('subscriber'));
    }

    /**
     * Subscribe a subscriber
     *
     * @param string $subscriberHash
     *
     * @return View
     */
    public function subscribe($subscriberHash)
    {
        $subscriber = $this->subscribers->findBy('hash', $subscriberHash);

        return view('subscriptions.subscribe', compact('subscriber'));
    }

    /**
     * Toggle subscriber subscription state
     *
     * @param SubscriptionToggleRequest $request
     * @param string $subscriberId
     *
     * @return RedirectResponse
     */
    public function update(SubscriptionToggleRequest $request, $subscriberId)
    {
        $subscriber = $this->subscribers->find((int)$subscriberId);

        $isUnsubscribed = (bool)$request->get('is_unsubscribed');

        $this->subscribers->update($subscriber->id, [
            'unsubscribed_at' => $isUnsubscribed ? Carbon::now() : null,
            'unsubscribe_event_id' => $isUnsubscribed ? UnsubscribeEventType::MANUAL_BY_SUBSCRIBER : null
        ]);

        if ($isUnsubscribed)
        {
            return redirect()->route('subscriptions.subscribe', $subscriber->hash);
        }

        return redirect()->route('subscriptions.unsubscribe', $subscriber->hash);
    }
}

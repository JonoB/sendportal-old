<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionToggleRequest;
use App\Repositories\SubscriberTenantRepository;
use App\Models\UnsubscribeEventType;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionsController extends Controller
{
    /**
     * @var SubscriberTenantRepository
     */
    protected $subscribers;

    /**
     * @param SubscriberTenantRepository $subscribers
     */
    public function __construct(SubscriberTenantRepository $subscribers)
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

        $unsubscribed = (bool)$request->get('unsubscribed');

        $this->subscribers->update($subscriber->id, [
            'unsubscribed_at' => $unsubscribed ? Carbon::now() : null,
            'unsubscribe_event_id' => $unsubscribed ? UnsubscribeEventType::MANUAL_BY_SUBSCRIBER : null
        ]);

        if ($unsubscribed)
        {
            return redirect()->route('subscriptions.subscribe', $subscriber->hash)
                ->with('success', 'You have been removed from the mailing list.');
        }

        return redirect()->route('subscriptions.unsubscribe', $subscriber->hash)
            ->with('success', 'You have been added to the mailing list.');
    }
}

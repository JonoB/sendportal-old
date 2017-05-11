<?php

namespace App\Http\Controllers;

use App\Interfaces\ContactNewsletterRepositoryInterface;
use App\Interfaces\ContactRepositoryInterface;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Interfaces\NewsletterUrlsRepositoryInterface;
use App\Repositories\NewsletterUrlsEloquentRepository;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    /**
     * @var ContactRepositoryInterface
     */
    protected $contactRepository;

    /**
     * UnsubscribeController constructor.
     *
     * @param ContactRepositoryInterface $contactRepository
     */
    public function __construct(
        ContactRepositoryInterface $contactRepository
    )
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * Track email opens
     *
     * @param Request $request
     * @param string $contactId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function unsubscribe(Request $request, $contactId)
    {
        if ( ! $this->contactRepository->find($contactId))
        {
            abort(404);
        }

        return view('subscriptions.unsubscribe', compact('contactId'));
    }

    public function update(Request $request)
    {
        $contactId = $request->get('contact_id');

        if ( ! $this->contactRepository->find($contactId))
        {
            abort(404);
        }

        $unsubscribed = $request->get('unsubscribed');

        $this->contactRepository->update($contactId, [
            'unsubscribed' => $unsubscribed,
        ]);

        if ($unsubscribed)
        {
            return redirect()->route('subscriptions.subscribe', $contactId);
        }

        return redirect()->route('subscriptions.unsubscribe', $contactId);
    }

    public function subscribe(Request $request, $contactId)
    {
        if ( ! $this->contactRepository->find($contactId))
        {
            abort(404);
        }

        return view('subscriptions.subscribe', compact('contactId'));
    }

 }

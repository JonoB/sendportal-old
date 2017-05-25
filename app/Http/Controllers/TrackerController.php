<?php

namespace App\Http\Controllers;

use App\Interfaces\NewsletterSubscriberRepositoryInterface;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Interfaces\NewsletterUrlsRepositoryInterface;
use App\Repositories\NewsletterUrlsEloquentRepository;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    /**
     * @var NewsletterSubscriberRepositoryInterface
     */
    protected $subscriberNewsletterRepo;

    /**
     * @var NewsletterUrlsRepositoryInterface
     */
    protected $newsletterUrlsRepo;

    /**
     * @var NewsletterRepositoryInterface
     */
    protected $newsletterRepo;

    /**
     * TrackerController constructor.
     *
     * @param NewsletterSubscriberRepositoryInterface $subscriberNewsletterRepo
     * @param NewsletterRepositoryInterface $newsletterRepository
     */
    public function __construct(
        NewsletterSubscriberRepositoryInterface $subscriberNewsletterRepo,
        NewsletterUrlsRepositoryInterface $newsletterUrlsRepository,
        NewsletterRepositoryInterface $newsletterRepository
    )
    {
        $this->subscriberNewsletterRepo = $subscriberNewsletterRepo;
        $this->newsletterUrlsRepo = $newsletterUrlsRepository;
        $this->newsletterRepo = $newsletterRepository;
    }

    /**
     * Track email opens
     *
     * @param Request $request
     * @param string $newsletterId
     * @param string $subscriberId
     * @return void
     */
    public function opens(Request $request, $newsletterId, $subscriberId)
    {

        header('Content-Type: image/gif');
        readfile(public_path('img/tracking.gif'));

        $this->subscriberNewsletterRepo->incrementOpenCount($newsletterId, $subscriberId, $request->ip());

        $totalOpenCount = $this->subscriberNewsletterRepo->getUniqueOpenCount($newsletterId);

        $this->newsletterRepo->update($newsletterId, [
            'open_count' => $totalOpenCount,
        ]);
    }

    /**
     * Track email clicks and redirect to original route
     *
     * @param Request $request
     * @param string $newsletterId
     * @param string $subscriberId
     * @param string $urlId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function clicks(Request $request, $newsletterId, $subscriberId, $urlId)
    {
        // store click count per url
        $this->newsletterUrlsRepo->incrementClickCount($urlId);

        // store click count per user
        $this->subscriberNewsletterRepo->incrementClickCount($newsletterId, $subscriberId);

        $totalNewsletterClickCount = $this->newsletterUrlsRepo->getTotalClickCount($newsletterId);

        $this->newsletterRepo->update($newsletterId, [
            'click_count' => $totalNewsletterClickCount,
        ]);

        $newsletterUrl = $this->newsletterUrlsRepo->find($urlId);

        return redirect($newsletterUrl->original_url);
    }
 }

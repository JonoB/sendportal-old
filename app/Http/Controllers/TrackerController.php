<?php

namespace App\Http\Controllers;

use App\Interfaces\NewsletterOpenRepositoryInterface;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Interfaces\NewsletterUrlsRepositoryInterface;
use App\Repositories\NewsletterUrlsEloquentRepository;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    /**
     * @var NewsletterOpenRepositoryInterface
     */
    protected $newsletterOpenRepository;

    /**
     * @var NewsletterUrlsRepositoryInterface
     */
    protected $newsletterUrlsRepository;

    /**
     * @var NewsletterRepositoryInterface
     */
    protected $newsletterRepository;

    /**
     * TrackerController constructor.
     *
     * @param NewsletterOpenRepositoryInterface $newsletterOpenRepository
     * @param NewsletterRepositoryInterface $newsletterRepository
     */
    public function __construct(
        NewsletterOpenRepositoryInterface $newsletterOpenRepository,
        NewsletterUrlsRepositoryInterface $newsletterUrlsRepository,
        NewsletterRepositoryInterface $newsletterRepository
    )
    {
        $this->newsletterOpenRepository = $newsletterOpenRepository;
        $this->newsletterUrlsRepository = $newsletterUrlsRepository;
        $this->newsletterRepository = $newsletterRepository;
    }

    /**
     * Track email opens
     *
     * @param Request $request
     * @param int $newsletterId
     * @param int $contactId
     * @return void
     */
    public function opens(Request $request, $newsletterId, $contactId)
    {
        $this->newsletterOpenRepository->storeOpenTrack($newsletterId, $contactId, $request->ip());

        $openCount = $this->newsletterOpenRepository->getUniqueOpenCount($newsletterId);

        $this->newsletterRepository->update($newsletterId, [
            'open_count' => $openCount,
        ]);
    }

    /**
     * Track email clicks and redirect to original route
     *
     * @param Request $request
     * @param int $newsletterId
     * @param int $urlId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function clicks(Request $request, $newsletterId, $urlId)
    {
        $this->newsletterUrlsRepository->storeClickTrack($urlId);

        $clickCount = $this->newsletterUrlsRepository->getTotalClickCount($newsletterId);

        $this->newsletterRepository->update($newsletterId, [
            'click_count' => $clickCount,
        ]);

        $newsletterUrl = $this->newsletterUrlsRepository->find($urlId);

        return redirect($newsletterUrl->original_url);
    }
 }

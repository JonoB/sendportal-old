<?php

namespace App\Http\Controllers;

use App\Interfaces\ContactNewsletterRepositoryInterface;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Interfaces\NewsletterUrlsRepositoryInterface;
use App\Repositories\NewsletterUrlsEloquentRepository;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    /**
     * @var ContactNewsletterRepositoryInterface
     */
    protected $contactNewsletterRepo;

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
     * @param ContactNewsletterRepositoryInterface $contactNewsletterRepo
     * @param NewsletterRepositoryInterface $newsletterRepository
     */
    public function __construct(
        ContactNewsletterRepositoryInterface $contactNewsletterRepo,
        NewsletterUrlsRepositoryInterface $newsletterUrlsRepository,
        NewsletterRepositoryInterface $newsletterRepository
    )
    {
        $this->contactNewsletterRepo = $contactNewsletterRepo;
        $this->newsletterUrlsRepo = $newsletterUrlsRepository;
        $this->newsletterRepo = $newsletterRepository;
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

        header('Content-Type: image/gif');
        readfile(public_path('img/tracking.gif'));

        $this->contactNewsletterRepo->incrementOpenCount($newsletterId, $contactId, $request->ip());

        $totalOpenCount = $this->contactNewsletterRepo->getUniqueOpenCount($newsletterId);

        $this->newsletterRepo->update($newsletterId, [
            'open_count' => $totalOpenCount,
        ]);
    }

    /**
     * Track email clicks and redirect to original route
     *
     * @param Request $request
     * @param string $newsletterId
     * @param string $contactId
     * @param string $urlId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function clicks(Request $request, $newsletterId, $contactId, $urlId)
    {
        // store click count per url
        $this->newsletterUrlsRepo->incrementClickCount($urlId);

        // store click count per user
        $this->contactNewsletterRepo->incrementClickCount($newsletterId, $contactId);

        $totalNewsletterClickCount = $this->newsletterUrlsRepo->getTotalClickCount($newsletterId);

        $this->newsletterRepo->update($newsletterId, [
            'click_count' => $totalNewsletterClickCount,
        ]);

        $newsletterUrl = $this->newsletterUrlsRepo->find($urlId);

        return redirect($newsletterUrl->original_url);
    }
 }

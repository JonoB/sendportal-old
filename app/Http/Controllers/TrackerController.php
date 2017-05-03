<?php

namespace App\Http\Controllers;

use App\Interfaces\NewsletterOpenRepositoryInterface;
use App\Interfaces\NewsletterRepositoryInterface;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    /**
     * @var NewsletterOpenRepositoryInterface
     */
    protected $newsletterOpenRepository;

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
        NewsletterRepositoryInterface $newsletterRepository
    )
    {
        $this->newsletterOpenRepository = $newsletterOpenRepository;
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

        $openCount = $this->newsletterOpenRepository->getOpenCount($newsletterId);

        $this->newsletterRepository->update($newsletterId, [
            'open_count' => $openCount,
        ]);
    }

    /**
     * Track email clicks
     *
     * @param Request $request
     * @param int $newsletterId
     * @param int $linkId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clicks(Request $request, $newsletterId, $linkId)
    {
    }
 }

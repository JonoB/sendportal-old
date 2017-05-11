<?php

namespace App\Http\Controllers;

use App\Interfaces\NewsletterReportServiceInterface;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Models\NewsletterStatus;
use Illuminate\Http\RedirectResponse;

class NewsletterReportsController extends Controller
{
    /**
     * @var NewsletterRepositoryInterface
     */
    protected $newsletterRepo;

    /**
     * @var NewsletterRepositoryInterface
     */
    protected $newsletterReportService;

    /**
     * NewslettersController constructor.
     *
     * @param NewsletterRepositoryInterface $newsletterRepository
     * @param TemplateRepositoryInterface $newsletterRepository#
     */
    public function __construct(
        NewsletterRepositoryInterface $newsletterRepository,
        NewsletterReportServiceInterface $newsletterReportService
    )
    {
        $this->newsletterRepo = $newsletterRepository;
        $this->newsletterReportService = $newsletterReportService;
    }

    /**
     * Show newsletter report view
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\View\View
     */
    public function report($id)
    {
        $newsletter = $this->newsletterRepo->find($id);

        if ($newsletter->status_id == NewsletterStatus::STATUS_DRAFT)
        {
            return redirect()->route('newsletters.edit', $id);
        }

        if ($newsletter->status_id != NewsletterStatus::STATUS_SENT)
        {
            return redirect()->route('newsletters.status', $id);

        }

        $chartData = $this->newsletterReportService->opensPerHour($id);
        $newsletterUrls = $this->newsletterReportService->newsletterUrls($id);

        return view('newsletters.report', compact('newsletter', 'chartData', 'newsletterUrls'));

    }

    /**
     * Show newsletter recipients view
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\View\View
     */
    public function recipients($id)
    {
        $newsletter = $this->newsletterRepo->find($id);

        if ($newsletter->status_id == NewsletterStatus::STATUS_DRAFT)
        {
            return redirect()->route('newsletters.edit', $id);
        }

        if ($newsletter->status_id == NewsletterStatus::STATUS_SENT)
        {
            $recipients = $this->contactNewsletterRepo->paginate('created_at', [], 50, ['newsletter_id' => $id]);

            return view('newsletters.recipients', compact('newsletter', $recipients));
        }

        return redirect()->route('newsletters.status', $id);
    }

}

<?php

namespace App\Services;

use App\Interfaces\ContactNewsletterRepositoryInterface;
use App\Interfaces\NewsletterReportServiceInterface;
use App\Interfaces\NewsletterUrlsRepositoryInterface;

class NewsletterReportService implements NewsletterReportServiceInterface
{
    /**
     * @var ContactNewsletterRepositoryInterface
     */
    protected $contactNewsletterRepository;

    /**
     * @var NewsletterUrlsRepositoryInterface
     */
    protected $newsletterUrlsRepository;

    /**
     * NewsletterReportService constructor.
     *
     * @param ContactNewsletterRepositoryInterface $contactNewsletterRepository
     * @param NewsletterUrlsRepositoryInterface $newsletterUrlsRepository
     */
    public function __construct(
        ContactNewsletterRepositoryInterface $contactNewsletterRepository,
        NewsletterUrlsRepositoryInterface $newsletterUrlsRepository
    )
    {
        $this->contactNewsletterRepository = $contactNewsletterRepository;
        $this->newsletterUrlsRepository = $newsletterUrlsRepository;
    }

    public function opensPerHour($newsletterId)
    {
        $opensPerHour = $this->contactNewsletterRepository->countUniqueOpensPerHour($newsletterId);

        $chartLabels = [];
        $chartData = [];
        foreach ($opensPerHour as $item)
        {
            $chartLabels[] = $item->opened_at;
            $chartData[] = $item->open_count;
        }

        return [
            'labels' => json_encode($chartLabels),
            'data' => json_encode($chartData),
        ];
    }

    public function newsletterUrls($newsletterId)
    {
        return $this->newsletterUrlsRepository->getBy('newsletter_id', $newsletterId);
    }


}

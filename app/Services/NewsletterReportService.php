<?php

namespace App\Services;

use App\Interfaces\NewsletterSubscriberRepositoryInterface;
use App\Interfaces\NewsletterReportServiceInterface;
use App\Interfaces\NewsletterUrlsRepositoryInterface;

class NewsletterReportService implements NewsletterReportServiceInterface
{
    /**
     * @var NewsletterSubscriberRepositoryInterface
     */
    protected $newsletterSubscriberRepository;

    /**
     * @var NewsletterUrlsRepositoryInterface
     */
    protected $newsletterUrlsRepository;

    /**
     * NewsletterReportService constructor.
     *
     * @param NewsletterSubscriberRepositoryInterface $newsletterSubscriberRepository
     * @param NewsletterUrlsRepositoryInterface $newsletterUrlsRepository
     */
    public function __construct(
        NewsletterSubscriberRepositoryInterface $newsletterSubscriberRepository,
        NewsletterUrlsRepositoryInterface $newsletterUrlsRepository
    )
    {
        $this->newsletterSubscriberRepository = $newsletterSubscriberRepository;
        $this->newsletterUrlsRepository = $newsletterUrlsRepository;
    }

    public function opensPerHour($newsletterId)
    {
        $opensPerHour = $this->newsletterSubscriberRepository->countUniqueOpensPerHour($newsletterId);

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

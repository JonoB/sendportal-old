<?php

namespace App\Repositories;

use App\Interfaces\NewsletterUrlsRepositoryInterface;
use App\Models\NewsletterUrl;

class NewsletterUrlsEloquentRepository extends BaseEloquentRepository implements NewsletterUrlsRepositoryInterface
{
    protected $modelName = NewsletterUrl::class;

    /**
     * Track an open record
     *
     * @param int $newsletterId
     * @param int $contactId
     * @return mixed
     */
    public function storeClickTrack($newsletterId, $urlId)
    {
        return $this->getNewInstance()
            ->where('newsletter_id', $newsletterId)
            ->where('id', $urlId)
            ->increment('counter');
    }

    /**
     * Return the click count for a single link
     *
     * @param int $newsletterId
     * @param int $urlId
     * @return int
     */
    public function getUrlClickCount($newsletterId, $urlId)
    {
        return $this->getNewInstance()
            ->where('newsletter_id', $newsletterId)
            ->where('id', $urlId)
            ->sum('counter');
    }

    /**
     * Return the total click count for a newsletter
     *
     * @param int $newsletterId
     * @return int
     */
    public function getTotalClickCount($newsletterId)
    {
        return $this->getNewInstance()
            ->where('newsletter_id', $newsletterId)
            ->sum('counter');
    }
}

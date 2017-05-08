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
     * @param string $urlId
     * @return mixed
     */
    public function storeClickTrack($urlId)
    {
        return $this->getNewInstance()
            ->where('id', $urlId)
            ->increment('counter');
    }

    /**
     * Return the click count for a single link
     *
     * @param string $urlId
     * @return int
     */
    public function getUrlClickCount($urlId)
    {
        return $this->getNewInstance()
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

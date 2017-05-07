<?php

namespace App\Repositories;

use App\Interfaces\NewsletterOpenRepositoryInterface;
use App\Models\NewsletterOpen;

class NewsletterOpenEloquentRepository extends BaseEloquentRepository implements NewsletterOpenRepositoryInterface
{
    protected $modelName = NewsletterOpen::class;

    /**
     * Track an open record
     *
     * @param int $newsletterId
     * @param int $contactId
     * @param string $ipAddress
     * @return mixed
     */
    public function storeOpenTrack($newsletterId, $contactId, $ipAddress)
    {
        return $this->getNewInstance()
            ->where('newsletter_id', $newsletterId)
            ->where('contact_id', $contactId)
            ->update([
                'counter' => \DB::raw('counter + 1'),
                'ip' => $ipAddress,
            ]);
    }

    /**
     * Return the open count for a newsletter
     *
     * @param int $newsletterId
     * @return int
     */
    public function getUniqueOpenCount($newsletterId)
    {
        return $this->getNewInstance()
            ->where('newsletter_id', $newsletterId)
            ->where('counter', '>', 0)
            ->count();
    }
}

<?php

namespace App\Repositories;

use App\Interfaces\ContactNewsletterRepositoryInterface;
use App\Models\ContactNewsletter;

class ContactNewsletterEloquentRepository extends BaseEloquentRepository implements ContactNewsletterRepositoryInterface
{
    protected $modelName = ContactNewsletter::class;

    /**
     * Track opens
     *
     * @param string $newsletterId
     * @param string $contactId
     * @param string $ipAddress
     * @return mixed
     */
    public function incrementOpenCount($newsletterId, $contactId, $ipAddress)
    {
        return $this->getNewInstance()
            ->where('newsletter_id', $newsletterId)
            ->where('contact_id', $contactId)
            ->update([
                'open_count' => \DB::raw('open_count + 1'),
                'ip' => $ipAddress,
            ]);
    }

    /**
     * Track clicks
     *
     * @param string $newsletterId
     * @param string $contactId
     * @return mixed
     */
    public function incrementClickCount($newsletterId, $contactId)
    {
        return $this->getNewInstance()
            ->where('newsletter_id', $newsletterId)
            ->where('contact_id', $contactId)
            ->increment('click_count');
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
            ->where('open_count', '>', 0)
            ->count();
    }

    public function countUniqueOpensPerHour($newsletterId)
    {
        return $this->getNewInstance()
            ->select(\DB::raw('COUNT(open_count) as open_count, DATE_FORMAT(opened_at, "%d-%b %k:00") as opened_at'))
            ->where('newsletter_id', $newsletterId)
            ->groupBy(\DB::raw('HOUR(opened_at)'))
            ->orderBy('opened_at')
            ->get();
    }
}

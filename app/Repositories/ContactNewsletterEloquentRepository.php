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
            ->where('counter', '>', 0)
            ->count();
    }
}

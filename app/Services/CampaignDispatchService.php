<?php

namespace App\Services;

use App\Factories\MailAdapterFactory;
use App\Interfaces\CampaignDispatchInterface;

class CampaignDispatchService implements CampaignDispatchInterface
{

    /**
     * @var MailAdapterFactory
     */
    protected $mailAdapter;

    /**
     * CampaignDispatchService constructor.
     *
     * @param MailAdapterFactory $mailAdapter
     */
    public function __construct
    (
        MailAdapterFactory $mailAdapter
    )
    {
        $this->mailAdapter = $mailAdapter;
    }

    /**
     * Send the campaign
     *
     * @param string $mailService
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     * @return mixed
     */
    public function send($mailService, $fromEmail, $toEmail, $subject, $content)
    {
        try
        {
            return $this->dispatch($mailService, $fromEmail, $toEmail, $subject, $content);
        }
        catch (\Exception $e)
        {
            \Log::error(json_encode($e->getMessage()));

            return false;
        }
    }

    /**
     * Dispatch the campaign via
     * the given mail service
     *
     * @param string $mailService
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     * @return \Aws\Result
     */
    protected function dispatch($mailService, $fromEmail, $toEmail, $subject, $content)
    {
        return $this->mailAdapter->adapter($mailService)->send($fromEmail, $toEmail, $subject, $content);
    }
}

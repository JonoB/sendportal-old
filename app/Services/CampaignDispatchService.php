<?php

namespace App\Services;

use App\Factories\MailAdapterFactory;
use App\Interfaces\CampaignDispatchInterface;
use Illuminate\Support\Facades\Log;

class CampaignDispatchService implements CampaignDispatchInterface
{
    /**
     * @var MailAdapterFactory
     */
    protected $mailAdapter;

    /**
     * @param MailAdapterFactory $mailAdapter
     */
    public function __construct(MailAdapterFactory $mailAdapter)
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
     *
     * @return string
     */
    public function send($mailService, $fromEmail, $toEmail, $subject, $content): string
    {
        try
        {
            return $this->dispatch($mailService, $fromEmail, $toEmail, $subject, $content);
        }
        catch (\Exception $e)
        {
            Log::error(json_encode($e->getMessage()));

            return false;
        }
    }

    /**
     * Dispatch the campaign via the given mail service
     *
     * @param string $mailService
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     *
     * @return string
     */
    protected function dispatch($mailService, $fromEmail, $toEmail, $subject, $content): string
    {
        return $this->mailAdapter->adapter($mailService)
            ->send($fromEmail, $toEmail, $subject, $content);
    }
}

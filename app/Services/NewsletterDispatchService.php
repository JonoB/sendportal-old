<?php

namespace App\Services;

use App\Interfaces\SesServiceInterface;
use App\Interfaces\NewsletterDispatchInterface;

class NewsletterDispatchService implements NewsletterDispatchInterface
{

    /**
     * @var SesServiceInterface
     */
    protected $sesService;

    /**
     * @param SesServiceInterface $sesService
     */
    public function __construct(
        SesServiceInterface $sesService
    )
    {
        $this->sesService = $sesService;
    }

    /**
     * Send the newsletter
     *
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     * @return mixed
     */
    public function send($fromEmail, $toEmail, $subject, $content)
    {
        try
        {
            return $this->dispatch($fromEmail, $toEmail, $subject, $content);
        }
        catch (\Exception $e)
        {
            \Log::error(json_encode($e->getMessage()));

            return false;
        }
    }

    /**
     * Dispatch the newsletter via ses
     *
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     * @return \Aws\Result
     */
    protected function dispatch($fromEmail, $toEmail, $subject, $content)
    {
        return $this->sesService->sendMail($fromEmail, [$toEmail], $subject, $content);
    }
}

<?php

namespace App\Services;

use App\Interfaces\NewsletterDispatchInterface;
use Aws\Ses\SesClient;

class NewsletterDispatchService implements NewsletterDispatchInterface
{
    /**
     * @var SesClient
     */
    protected $sesClient;

    /**
     * NewsletterDispatchService constructor.
     */
    public function __construct()
    {
        $this->sesClient = $this->createSesClient();
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
        return $this->sesClient->sendEmail([
            'Source' => $fromEmail,

            'Destination' => [
                'ToAddresses' => [$toEmail],
            ],

            'Message' => [
                'Subject' => [
                    'Data' => $subject,
                ],
                'Body' => array(
                    'Html' => [
                        'Data' => $content,
                    ],
                ),
            ],
        ]);
    }

    /**
     * Create a new SesClient
     *
     * @return SesClient
     */
    protected function createSesClient()
    {
       return app()->make('aws')->createClient('ses');
    }
}

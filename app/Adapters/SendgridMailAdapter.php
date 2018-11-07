<?php

namespace App\Adapters;

use App\Interfaces\MailAdapterInterface;
use SendGrid;
use SendGrid\Mail\Mail;

class SendgridMailAdapter implements MailAdapterInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var
     */
    protected $client;

    /**
     * Resolve a Sendgrid Client
     *
     * @param null
     * @return SendGrid
     */
    public function resolveClient()
    {
        if ($this->client)
        {
            return $this->client;
        }

        $this->client = new SendGrid(array_get($this->config, 'key'));

        return $this->client;
    }

    /**
     * Set adapter config
     *
     * @param array $config
     * @return null
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Send the e-mail using SesClient
     *
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     * @return bool
     * @throws \SendGrid\Mail\TypeException
     */
    public function send($fromEmail, $toEmail, $subject, $content)
    {
        $email = new Mail();
        $email->setFrom($fromEmail);
        $email->setSubject($subject);
        $email->addTo($toEmail);
        $email->addContent("text/html",  $content);

        try
        {
            $response = $this->resolveClient()->send($email);
        }
        catch (\Exception $e)
        {
            \Log::error('Failed to send via SendGrid', ['error' => $e->getMessage()]);
        }

        return $response->statusCode() == 202;
    }
}

<?php

namespace App\Adapters;

use App\Interfaces\MailAdapterInterface;
use GuzzleHttp\Client;

class SendgridMailAdapter implements MailAdapterInterface
{
    const BASE_URL = 'https://api.sendgrid.com/v3/mail/send';

    /**
     * @var array
     */
    protected $config;

    /**
     * @var
     */
    protected $client;

    /**
     * Resolve a Guzzle Client
     *
     * @param null
     * @return Client
     */
    public function resolveClient()
    {
        if ($this->client)
        {
            return $this->client;
        }

        $this->client = new Client();

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
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send($fromEmail, $toEmail, $subject, $content)
    {
        $payload = [
            'headers' => [
                'Authorization' => 'Bearer ' . array_get($this->config, 'key'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'personalizations' => [
                    ['to' => $toEmail],
                ],
                'from' => $fromEmail,
                'subject' => $subject,
                'content' =>[
                    'type'  => 'text/html',
                    'value' => $content,
                ],
            ],
        ];

        return $this->resolveClient()->post(static::BASE_URL, $payload);
    }
}

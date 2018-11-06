<?php

namespace App\Adapters;

use App\Interfaces\MailAdapterInterface;
use Aws\Ses\SesClient;

class SesMailAdapter implements MailAdapterInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var SesClient
     */
    protected $client;

    /**
     * @param array $config
     */
    public function __construct
    (
        $config
    )
    {
        $this->config = $config;

        $this->resolveClient();
    }

    /**
     * Resolve an SesClient
     *
     * @param null
     * @return null
     */
    public function resolveClient()
    {
        $this->client = app()->make('aws')->createClient('ses', [
            'region' => array_get($this->config, 'region'),
            'credentials' => [
                'key' => array_get($this->config, 'key'),
                'secret' => array_get($this->config, 'secret'),
            ]
        ]);
    }

    /**
     * Send the e-mail using SesClient
     *
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     * @return \Aws\Result
     */
    public function send($fromEmail, $toEmail, $subject, $content)
    {
        return $this->client->sendEmail([
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
}

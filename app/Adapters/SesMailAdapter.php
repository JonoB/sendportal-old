<?php

namespace App\Adapters;

use App\Interfaces\MailAdapterInterface;
use Aws\Ses\SesClient;

class SesMailAdapter extends BaseMailAdapter implements MailAdapterInterface
{
    /**
     * @var SesClient
     */
    protected $client;

    /**
     * Resolve an SesClient
     *
     * @param null
     * @return SesClient
     */
    public function resolveClient()
    {
        if ($this->client)
        {
            return $this->client;
        }

        $this->client = app()->make('aws')->createClient('ses', [
            'region' => array_get($this->config, 'region'),
            'credentials' => [
                'key' => array_get($this->config, 'key'),
                'secret' => array_get($this->config, 'secret'),
            ]
        ]);

        return $this->client;
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
        $result = $this->resolveClient()->sendEmail([
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

        return array_get($result->toArray(), 'MessageId');
    }
}

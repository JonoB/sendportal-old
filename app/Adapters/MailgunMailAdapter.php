<?php

namespace App\Adapters;

use App\Interfaces\MailAdapterInterface;
use Mailgun\Mailgun;

class MailgunMailAdapter implements MailAdapterInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var Mailgun
     */
    protected $client;

    /**
     * Resolve an SesClient
     *
     * @param null
     * @return Mailgun
     */
    public function resolveClient()
    {
        if ($this->client)
        {
            return $this->client;
        }

        $this->client = Mailgun::create(array_get($this->config, 'key'));

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
     * @return \Mailgun\Model\Message\SendResponse
     */
    public function send($fromEmail, $toEmail, $subject, $content)
    {
        return $this->resolveClient()->messages()->send(array_get($this->config, 'domain'), [
            'from'    => $fromEmail,
            'to'      => $toEmail,
            'subject' => $subject,
            'text'    => $content,
        ]);
    }
}

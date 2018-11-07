<?php

namespace App\Adapters;

use App\Interfaces\MailAdapterInterface;
use Postmark\PostmarkClient;

class PostmarkMailAdapter extends BaseMailAdapter implements MailAdapterInterface
{
    /**
     * @var PostmarkClient
     */
    protected $client;

    /**
     * Resolve an SesClient
     *
     * @param null
     * @return PostmarkClient
     */
    public function resolveClient()
    {
        if ($this->client)
        {
            return $this->client;
        }

        $this->client = new PostmarkClient(array_get($this->config, 'key'));;

        return $this->client;
    }

    /**
     * Send the e-mail using SesClient
     *
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     * @return \Postmark\Models\DynamicResponseModel
     */
    public function send($fromEmail, $toEmail, $subject, $content)
    {
        return $this->resolveClient()->sendEmail($fromEmail, $toEmail, $subject, $content);
    }
}

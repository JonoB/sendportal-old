<?php

namespace App\Adapters;

use App\Interfaces\MailAdapterInterface;
use Mailgun\Mailgun;
use Mailgun\Model\Message\SendResponse;

class MailgunMailAdapter extends BaseMailAdapter implements MailAdapterInterface
{
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
    protected function resolveClient()
    {
        if ($this->client)
        {
            return $this->client;
        }

        $this->client = Mailgun::create(array_get($this->config, 'key'));

        return $this->client;
    }

    /**
     * Send the e-mail using SesClient
     *
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     * @return string
     */
    public function send($fromEmail, $toEmail, $subject, $content)
    {
        $result = $this->resolveClient()->messages()->send(array_get($this->config, 'domain'), [
            'from'    => $fromEmail,
            'to'      => $toEmail,
            'subject' => $subject,
            'html'    => $content,
        ]);

        return $this->resolveMessageId($result);
    }

    /**
     * Resolve the message ID
     * from the response
     *
     * @param SendResponse $result
     * @return string
     */
    protected function resolveMessageId(SendResponse $result)
    {
        return $result->getId();
    }
}

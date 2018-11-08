<?php

namespace App\Adapters;

use App\Interfaces\MailAdapterInterface;
use SendGrid;
use SendGrid\Mail\Mail;

class SendgridMailAdapter extends BaseMailAdapter implements MailAdapterInterface
{
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
    protected function resolveClient()
    {
        if ($this->client)
        {
            return $this->client;
        }

        $this->client = new SendGrid(array_get($this->config, 'key'));

        return $this->client;
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

            return false;
        }

        return $this->resolveMessageId($response);
    }

    /**
     * Resolve the message ID
     * from the response
     *
     * @param SendGrid\Response $response
     * @return string
     */
    protected function resolveMessageId(Sendgrid\Response $response)
    {
        $response = array_get($response->headers(), 8);

        return str_replace('X-Message-Id: ', '', $response);
    }
}

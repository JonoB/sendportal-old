<?php

namespace App\Adapters;

use App\Interfaces\MailAdapterInterface;
use Postmark\Models\DynamicResponseModel;
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
    protected function resolveClient()
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
     * @return string
     */
    public function send($fromEmail, $toEmail, $subject, $content)
    {
        $result = $this->resolveClient()->sendEmail($fromEmail, $toEmail, $subject, $content);

        return $this->resolveMessageId($result);
    }

    /**
     * Resolve the message ID
     * from the response
     *
     * @param DynamicResponseModel $result
     * @return string
     */
    protected function resolveMessageId(DynamicResponseModel $result)
    {
        return $result->__get('MessageID');
    }
}

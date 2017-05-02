<?php

namespace App\Services;



use App\Models\Contact;
use App\Models\Newsletter;
use Aws\Ses\SesClient;

class NewsletterDispatchService
{
    /**
     * @var SesClient
     */
    protected $sesClient;

    /**
     * @var Newsletter
     */
    protected $newsletter;

    /**
     * @var Contact
     */
    protected $contact;

    public function __construct()
    {
        $this->sesClient = $this->createSesClient();
    }

    /**
     * Invoke the send method
     *
     * @param Newsletter $newsletter
     * @param Contact $contact
     * @return \Aws\Result
     */
    public function send(Newsletter $newsletter, Contact $contact)
    {
        $result = $this->dispatch($newsletter, $contact);

        \Log::info(json_encode($result));

        return $result;
    }

    /**
     * Dispatch the email via ses
     *
     * @param Newsletter $newsletter
     * @param Contact $contact
     * @return \Aws\Result
     */
    public function dispatch(Newsletter $newsletter, Contact $contact)
    {
        return $this->sesClient->sendEmail([
            'Source' => $newsletter->from_email,

            'Destination' => [
                'ToAddresses' => [$contact->email],
            ],

            'Message' => [
                'Subject' => [
                    'Data' => $newsletter->subject,
                ],
                'Body' => array(
                    'Html' => [
                        'Data' => $newsletter->content,
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
       return new SesClient([
           'version' => 'latest',
           'region'  => env('aws.region'),
       ]);
    }
}

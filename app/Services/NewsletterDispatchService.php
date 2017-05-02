<?php

namespace App\Services;

use App\Interfaces\NewsletterOpenRepositoryInterface;
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
     * @var NewsletterOpenRepositoryInterface
     */
    protected $newsletterOpenRepositoryInterface;

    public function __construct()
    {
        $this->sesClient = $this->createSesClient();
        $this->newsletterOpenRepositoryInterface = app()->make(NewsletterOpenRepositoryInterface::class);
    }

    /**
     * Invoke the send method
     *
     * @param Newsletter $newsletter
     * @param Contact $contact
     * @return \Aws\Result|bool
     */
    public function send(Newsletter $newsletter, Contact $contact)
    {
        try
        {
            $result = $this->dispatch($newsletter, $contact);

            $this->insertOpenTrackingRecord($newsletter, $contact);

            return $result;
        }
        catch (\Exception $e)
        {
            //@todo catch it!

            return false;
        }
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
     * Create tracking record
     *
     * @param Newsletter $newsletter
     * @param Contact $contact
     * @return void
     */
    protected function insertOpenTrackingRecord(Newsletter $newsletter, Contact $contact)
    {
        $this->newsletterOpenRepositoryInterface->store([
            'newsletter_id' => $newsletter->id,
            'contact_id' => $contact->id,
        ]);
    }

    /**
     * Create a new SesClient
     *
     * @return SesClient
     */
    protected function createSesClient()
    {
       return app()->make('aws')->createClient('ses');
    }
}

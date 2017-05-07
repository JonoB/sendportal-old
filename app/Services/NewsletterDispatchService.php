<?php

namespace App\Services;

use App\Interfaces\GenerateOpenTrackingImageInterface;
use App\Interfaces\NewsletterDispatchInterface;
use App\Interfaces\NewsletterOpenRepositoryInterface;
use App\Models\Contact;
use App\Models\Newsletter;
use Aws\Ses\SesClient;

class NewsletterDispatchService implements NewsletterDispatchInterface
{
    /**
     * @var SesClient
     */
    protected $sesClient;

    /**
     * @var NewsletterOpenRepositoryInterface
     */
    protected $newsletterOpenRepositoryInterface;

    /**
     * @var GenerateOpenTrackingImageInterface
     */
    protected $openTrackingImageService;

    /**
     * NewsletterDispatchService constructor.
     */
    public function __construct()
    {
        $this->sesClient = $this->createSesClient();
        $this->newsletterOpenRepositoryInterface = app()->make(NewsletterOpenRepositoryInterface::class);
        $this->openTrackingImageService = app()->make(GenerateOpenTrackingImageInterface::class);
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

            $this->createDatabaseRecord($newsletter, $contact);

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
    protected function dispatch(Newsletter $newsletter, Contact $contact)
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
                        'Data' => $this->generateContent($newsletter, $contact),
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
    protected function createDatabaseRecord(Newsletter $newsletter, Contact $contact)
    {
        $this->newsletterOpenRepositoryInterface->store([
            'newsletter_id' => $newsletter->id,
            'contact_id' => $contact->id,
        ]);
    }

    /**
     * Generate newsletter content
     *
     * @param Newsletter $newsletter
     * @param Contact $contact
     * @return string
     */
    protected function generateContent(Newsletter $newsletter, Contact $contact)
    {
        $content = $this->embedOpenTrackingImage($newsletter, $contact);

        return $this->mergeTags($content, $contact);
    }

    /**
     * Embed the open tracking image
     *
     * @param Newsletter $newsletter
     * @param Contact $contact
     * @return string
     */
    protected function embedOpenTrackingImage(Newsletter $newsletter, Contact $contact)
    {
        if ( ! $newsletter->track_opens)
        {
            return $newsletter->content;
        }

        $image = $this->openTrackingImageService->generate($newsletter, $contact);

        return str_replace('</body>', $image . '</body>', $newsletter->content);
    }

    /**
     * Merge tags for the contact
     *
     * @param string $content
     * @param Contact $contact
     * @return string
     */
    protected function mergeTags($content, Contact $contact)
    {
        $tags = [
            'Email' => $contact->email,
            'FirstName' => $contact->first_name,
            'LastName' => $contact->last_name,
        ];

        // regex doesn't seem to work here - I think it
        // may be due to all the tags and inverted commas in html?
        foreach ($tags as $key => $value)
        {
            //$pattern = '/{{\s?' . $key . '\s?}}/i';

            //preg_replace($pattern, $value, $content);
        }

        foreach ($tags as $key => $value)
        {
            $search = [
                '{{' . $key . '}}',
                '{{ ' . $key . ' }}',
            ];
            $content = str_ireplace($search, $value, $content);
        }

        return $content;
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

<?php

namespace App\Services;

use App\Interfaces\ConfigRepositoryInterface;
use App\Interfaces\CampaignDispatchInterface;
use Aws\Ses\SesClient;
use App\Models\ConfigType;

class CampaignDispatchService implements CampaignDispatchInterface
{

    /**
     * @var ConfigRepositoryInterface
     */
    protected $configRepo;

    /**
     * @var SesClient
     */
    protected $sesClient;

    /**
     * CampaignDispatchService constructor.
     *
     * @param ConfigRepositoryInterface $configRepo
     */
    public function __construct(
        ConfigRepositoryInterface $configRepo
    )
    {
        $this->configRepo = $configRepo;
    }

    /**
     * Send the campaign
     *
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     * @return mixed
     */
    public function send($fromEmail, $toEmail, $subject, $content)
    {
        try
        {
            return $this->dispatch($fromEmail, $toEmail, $subject, $content);
        }
        catch (\Exception $e)
        {
            \Log::error(json_encode($e->getMessage()));

            return false;
        }
    }

    /**
     * Dispatch the campaign via ses
     *
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     * @return \Aws\Result
     */
    protected function dispatch($fromEmail, $toEmail, $subject, $content)
    {
        return $this->createSesClient()->sendEmail([
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

    /**
     * Create a new SesClient
     *
     * @return SesClient
     */
    protected function createSesClient()
    {
        if ($this->sesClient)
        {
            return $this->sesClient;
        }

        $settings = $this->configRepo->findSettings(ConfigType::AWS_SNS);

        return app()->make('aws')->createClient('ses', [
            'region' => array_get($settings, 'region'),
            'credentials' => [
                'key' => array_get($settings, 'key'),
                'secret' => array_get($settings, 'secret'),
            ]
        ]);
    }
}

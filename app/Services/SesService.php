<?php

namespace App\Services;

use App\Interfaces\SesServiceInterface;
use App\Interfaces\ConfigRepositoryInterface;
use Aws\Ses\SesClient;
use App\Models\ConfigType;

class SesService implements SesServiceInterface
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
     * @param ConfigRepositoryInterface $configRepo
     */
    public function __construct(
        ConfigRepositoryInterface $configRepo
    )
    {
        $this->configRepo = $configRepo;
    }

    /**
     * @param $fromEmail
     * @param array $toEmail
     * @param $subject
     * @param $content
     * @return \Aws\Result
     */
    public function sendMail($fromEmail, array $toEmail, $subject, $content)
    {
        return $this->client()->sendEmail([
            'Source' => $fromEmail,
            'Destination' => [
                'ToAddresses' => $toEmail,
            ],
            'Message' => [
                'Subject' => [
                    'Data' => $subject,
                ],
                'Body' => [
                    'Html' => [
                        'Data' => $content,
                    ]
                ]
            ],
        ]);
    }

    /**
     * @return SesClient
     */
    protected function client()
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

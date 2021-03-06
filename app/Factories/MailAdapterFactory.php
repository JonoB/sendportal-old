<?php

namespace App\Factories;

use App\Adapters\MailgunMailAdapter;
use App\Adapters\PostmarkMailAdapter;
use App\Adapters\SendgridMailAdapter;
use App\Adapters\SesMailAdapter;
use App\Interfaces\ProviderRepositoryInterface;
use App\Interfaces\MailAdapterInterface;
use App\Models\ProviderType;

class MailAdapterFactory
{
    /**
     * The array of resolved mail adapters.
     *
     * @var array
     */
    protected $adapters = [];

    /**
     * @var ProviderRepositoryInterface
     */
    protected $providerRepo;

    /**
     * @param ProviderRepositoryInterface $providerRepo
     */
    public function __construct(ProviderRepositoryInterface $providerRepo)
    {
        $this->providerRepo = $providerRepo;
    }

    /**
     * Get a mail adapter instance by name.
     *
     * @param  string $name
     * @return MailAdapterInterface
     */
    public function store($name)
    {
        return $this->adapters[$name] = $this->get($name);
    }

    /**
     * Get a mail adapter instance.
     *
     * @param  string $adapter
     * @return MailAdapterInterface
     */
    public function adapter($adapter)
    {
        return $this->store($adapter);
    }

    /**
     * Attempt to get the adapter from the local cache.
     *
     * @param  string $name
     * @return MailAdapterInterface
     */
    protected function get($name)
    {
        return $this->adapters[$name] ?? $this->resolve($name);
    }

    /**
     * Resolve the given adapter.
     *
     * @param  string $name
     * @return MailAdapterInterface
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $adapterMethod = 'create' . ucfirst($name) . 'Adapter';

        if (method_exists($this, $adapterMethod))
        {
            return $this->{$adapterMethod}();
        }

        throw new \InvalidArgumentException("Mail adapter [{$name}] is not supported.");
    }

    /**
     * Return an AWS SesMailAdapter
     *
     * @param null
     * @return MailAdapterInterface
     */
    public function createAwssqsAdapter()
    {
        $adapter = new SesMailAdapter();

        $config = $this->providerRepo->findSettings(ProviderType::AWS_SNS);

        $adapter->setConfig($config);

        return $adapter;
    }

    /**
     * Return a SendgridMailAdapter
     *
     * @param null
     * @return MailAdapterInterface
     */
    public function createSendgridAdapter()
    {
        $adapter = new SendgridMailAdapter();

        $config = $this->providerRepo->findSettings(ProviderType::SENDGRID);

        $adapter->setConfig($config);

        return $adapter;
    }

    /**
     * Return a MailgunMailAdapter
     *
     * @param null
     * @return MailAdapterInterface
     */
    public function createMailgunAdapter()
    {
        $adapter = new MailgunMailAdapter();

        $config = $this->providerRepo->findSettings(ProviderType::MAILGUN);

        $adapter->setConfig($config);

        return $adapter;
    }

    /**
     * Return a PostmarkMailAdapter
     *
     * @param null
     * @return MailAdapterInterface
     */
    public function createPostmarkAdapter()
    {
        $adapter = new PostmarkMailAdapter();

        $config = $this->providerRepo->findSettings(ProviderType::POSTMARK);

        $adapter->setConfig($config);

        return $adapter;
    }
}

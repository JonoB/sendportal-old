<?php

namespace App\Factories;

use App\Adapters\SesMailAdapter;
use App\Interfaces\ConfigRepositoryInterface;
use App\Interfaces\MailAdapterInterface;
use App\Models\ConfigType;

class MailAdapterFactory
{
    /**
     * The array of resolved mail adapters.
     *
     * @var array
     */
    protected $adapters = [];

    /**
     * @var ConfigRepositoryInterface
     */
    protected $configRepo;

    /**
     * @param ConfigRepositoryInterface $configRepo
     */
    public function __construct
    (
        ConfigRepositoryInterface $configRepo
    )
    {
        $this->configRepo = $configRepo;
    }

    /**
     * Get a mail adapter instance by name.
     *
     * @param  string  $name
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
     * @param  string  $name
     * @return MailAdapterInterface
     */
    protected function get($name)
    {
        return $this->adapters[$name] ?? $this->resolve($name);
    }

    /**
     * Resolve the given adapter.
     *
     * @param  string  $name
     * @return MailAdapterInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $adapterMethod = 'create' . ucfirst($name) . 'Adapter';

        if (method_exists($this, $adapterMethod)) {
            return $this->{$adapterMethod}();
        } else {
            throw new \InvalidArgumentException("Mail adapter [{$name}] is not supported.");
        }
    }

    /**
     * Return an AWS SesMailAdapter
     *
     * @param null
     * @return MailAdapterInterface
     */
    public function createSesAdapter()
    {
        $config = $this->configRepo->findSettings(ConfigType::AWS_SNS);

        return new SesMailAdapter($config);
    }
}

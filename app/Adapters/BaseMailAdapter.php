<?php

namespace App\Adapters;

use App\Interfaces\MailAdapterInterface;

abstract class BaseMailAdapter implements MailAdapterInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Set adapter config
     *
     * @param array $config
     * @return null
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }
}

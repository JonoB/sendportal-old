<?php

namespace App\Interfaces;


interface ProviderRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getProviderTypes();

    /**
     * @param $providerTypeId
     * @return mixed
     */
    public function findType($providerTypeId);

    /**
     * @param $providerTypeId
     * @return array
     */
    public function findSettings($providerTypeId);
}

<?php

namespace App\Interfaces;


interface ConfigRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getConfigTypes();

    /**
     * @param $configTypeId
     * @return mixed
     */
    public function findType($configTypeId);

    /**
     * @param $configTypeId
     * @return array
     */
    public function findSettings($configTypeId);

    /**
     * @param $configTypeId
     * @param array $newSettings
     * @return mixed
     */
    public function updateSettings($configTypeId, array $newSettings);
}

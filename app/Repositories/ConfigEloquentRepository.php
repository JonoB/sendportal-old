<?php

namespace App\Repositories;

use App\Interfaces\ConfigRepositoryInterface;
use App\Models\Config;
use App\Models\ConfigType;

class ConfigEloquentRepository extends BaseEloquentRepository implements ConfigRepositoryInterface
{

    /**
     * @var string
     */
    protected $modelName = Config::class;

    /**
     * @return mixed
     */
    public function getConfigTypes()
    {
        return ConfigType::orderBy('name')->get();
    }

    /**
     * @param $configTypeId
     * @return mixed
     */
    public function findType($configTypeId)
    {
        return ConfigType::findOrFail($configTypeId);
    }

    /**
     * @param $configTypeId
     * @return array
     */
    public function findSettings($configTypeId)
    {
        if ($config = Config::where('type_id', $configTypeId)->first())
        {
            return $config->settings;
        }

        return [];
    }

    /**
     * @param $configTypeId
     * @param array $newSettings
     * @return mixed
     */
    public function updateSettings($configTypeId, array $newSettings)
    {
        return Config::updateOrCreate(
            ['type_id' => $configTypeId],
            ['settings' => $newSettings]
        );
    }
}

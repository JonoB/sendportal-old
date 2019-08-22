<?php

namespace App\Repositories;

use App\Interfaces\ProviderRepositoryInterface;
use App\Models\Provider;
use App\Models\ProviderType;

class ProviderTenantRepository extends BaseTenantRepository implements ProviderRepositoryInterface
{

    /**
     * @var string
     */
    protected $modelName = Provider::class;

    /**
     * @return mixed
     */
    public function getProviderTypes()
    {
        return ProviderType::orderBy('name')->get();
    }

    /**
     * @param $providerTypeId
     * @return mixed
     */
    public function findType($providerTypeId)
    {
        return ProviderType::findOrFail($providerTypeId);
    }

    /**
     * @param $providerTypeId
     * @return array
     */
    public function findSettings($providerTypeId)
    {
        if ($provider = Provider::where('type_id', $providerTypeId)->first())
        {
            return $provider->settings;
        }

        return [];
    }
}

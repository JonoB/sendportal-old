<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProviderStoreRequest;
use App\Http\Requests\ProviderUpdateRequest;
use App\Repositories\ProviderTenantRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProvidersController extends Controller
{
    /**
     * @var ProviderTenantRepository
     */
    protected $providers;

    /**
     * ProviderController constructor.
     *
     * @param ProviderTenantRepository $providers
     */
    public function __construct
    (
        ProviderTenantRepository $providers
    )
    {
        $this->providers = $providers;
    }

    /**
     * @return Factory|View
     * @throws Exception
     */
    public function index()
    {
        $providers = $this->providers->all(currentTeamId());

        return view('providers.index', compact('providers'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        $providerTypes = $this->providers->getProviderTypes()->pluck('name', 'id');

        return view('providers.create', compact('providerTypes'));
    }

    /**
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(ProviderStoreRequest $request): RedirectResponse
    {
        $providerType = $this->providers->findType($request->type_id);

        $settings = $request->only(array_values($providerType->fields));

        $this->providers->store(currentTeamId(), [
            'name' => $request->name,
            'type_id' => $providerType->id,
            'settings' => $settings,
        ]);

        return redirect()->route('providers.index');
    }

    /**
     * @return Factory|View
     * @throws Exception
     */
    public function edit(int $providerId)
    {
        $providerTypes = $this->providers->getProviderTypes()->pluck('name', 'id');
        $provider = $this->providers->find(currentTeamId(), $providerId);
        $providerType = $this->providers->findType($provider->type_id);

        return view('providers.edit', compact('providerTypes', 'provider', 'providerType'));
    }

    /**
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(ProviderUpdateRequest $request, int $providerId): RedirectResponse
    {
        $provider = $this->providers->find(currentTeamId(), $providerId, ['type']);

        $settings = $request->only(array_values($provider->type->fields));

        $provider->name = $request->name;
        $provider->settings = $settings;
        $provider->save();

        return redirect()->route('providers.index');
    }

    /**
     * @return RedirectResponse
     * @throws Exception
     */
    public function delete(int $providerId): RedirectResponse
    {
        $provider = $this->providers->find(currentTeamId(), $providerId, ['campaigns']);

        if ($provider->in_use)
        {
            return redirect()->back()->withErrors("You cannot delete a provider that is currently used by a campaign or automation.");
        }

        $this->providers->destroy(currentTeamId(), $providerId);

        return redirect()->route('providers.index');
    }

    public function providersTypeAjax($providerTypeId): JsonResponse
    {
        $providerType = $this->providers->findType($providerTypeId);

        return response()->json($providerType->fields);
    }
}

<?php

namespace App\Http\Controllers;

use App\Interfaces\ProviderRepositoryInterface;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProvidersController extends Controller
{

    /**
     * @var ProviderRepositoryInterface
     */
    protected $providerRepo;

    /**
     * ProviderController constructor.
     *
     * @param ProviderRepositoryInterface $providerRepo
     */
    public function __construct
    (
        ProviderRepositoryInterface $providerRepo
    )
    {
        $this->providerRepo = $providerRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = $this->providerRepo->all();

        return view('providers.index', compact('providers'));
    }

    /**
     * Display the form for creating
     * a provider configuration set
     *
     * @param null
     * @return null
     */
    public function create()
    {
        $providerTypes = $this->providerRepo->getProviderTypes()->pluck('name', 'id');

        return view('providers.create', compact('providerTypes'));
    }

    /**
     * Store a new provider configuration set
     *
     * @param Request $request
     * @return null
     */
    public function store(Request $request)
    {
        $providerType = $this->providerRepo->findType($request->type_id);

        $settings = $request->only(array_values($providerType->fields));

        $this->providerRepo->store([
            'name' => $request->name,
            'type_id' => $providerType->id,
            'settings' => $settings,
        ]);

        return redirect()->route('providers.index');
    }

    /**
     * @param $providerId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($providerId)
    {
        $providerTypes = $this->providerRepo->getProviderTypes()->pluck('name', 'id');
        $provider = $this->providerRepo->find($providerId);

        $providerType = $this->providerRepo->findType($provider->type_id);
        // $settings = $this->providerRepo->findSettings($provider->type_id);

        return view('providers.edit', compact('providerTypes', 'provider', 'providerType'));
    }

    /**
     * @param Request $request
     * @param integer $providerId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $providerId)
    {
        $provider = $this->providerRepo->find($providerId, ['type']);

        $settings = $request->only(array_values($provider->type->fields));

        $provider->name = $request->name;
        $provider->settings = $settings;
        $provider->save();

        return redirect()->route('providers.index');
    }

    /**
     * Return the fields for
     * a given ProviderType
     *
     * @param integer $providerTypeId
     * @return null
     */
    public function providersTypeAjax($providerTypeId)
    {
        $providerType = $this->providerRepo->findType($providerTypeId);

        return response()->json($providerType->fields);
    }
}

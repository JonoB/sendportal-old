<?php

namespace App\Http\Controllers;

use App\Interfaces\ConfigRepositoryInterface;
use App\Models\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{

    /**
     * @var ConfigRepositoryInterface
     */
    protected $configRepo;

    /**
     * ConfigController constructor.
     *
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $configurations = $this->configRepo->all();

        return view('config.index', compact('configurations'));
    }

    /**
     * Display the form for creating
     * a configuration set
     *
     * @param null
     * @return null
     */
    public function create()
    {
        $configTypes = $this->configRepo->getConfigTypes()->pluck('name', 'id');

        return view('config.create', compact('configTypes'));
    }

    /**
     * Store a new configuration set
     *
     * @param Request $request
     * @return null
     */
    public function store(Request $request)
    {
        $configType = $this->configRepo->findType($request->type_id);

        $settings = $request->only(array_values($configType->fields));

        $this->configRepo->store([
            'name' => $request->name,
            'type_id' => $configType->id,
            'settings' => $settings,
        ]);

        return redirect()->route('config.index');
    }

    /**
     * @param $configId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($configId)
    {
        $configTypes = $this->configRepo->getConfigTypes()->pluck('name', 'id');
        $config = $this->configRepo->find($configId);

        $configType = $this->configRepo->findType($config->type_id);
        // $settings = $this->configRepo->findSettings($config->type_id);

        return view('config.edit', compact('configTypes', 'config', 'configType'));
    }

    /**
     * @param Request $request
     * @param integer $configId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $configId)
    {
        $config = $this->configRepo->find($configId, ['type']);

        $settings = $request->only(array_values($config->type->fields));

        $config->name = $request->name;
        $config->settings = $settings;
        $config->save();

        return redirect()->route('config.index');
    }

    /**
     * Return the fields for
     * a given ConfigType
     *
     * @param integer $configTypeId
     * @return null
     */
    public function configTypeAjax($configTypeId)
    {
        $configType = $this->configRepo->findType($configTypeId);

        return response()->json($configType->fields);
    }
}

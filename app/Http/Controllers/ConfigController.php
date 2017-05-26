<?php

namespace App\Http\Controllers;

use App\Interfaces\ConfigRepositoryInterface;
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
    public function __construct(
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
        $configurations = $this->configRepo->getConfigTypes();

        return view('configurations.index', compact('configurations'));
    }

    /**
     * @param $configTypeId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($configTypeId)
    {
        $configType = $this->configRepo->findType($configTypeId);
        $settings = $this->configRepo->findSettings($configTypeId);

        return view('configurations.edit', compact('settings', 'configType'));
    }

    /**
     * @param Request $request
     * @param $configTypeId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $configTypeId)
    {
        $configType = $this->configRepo->findType($configTypeId);
        $newSettings = $request->only(array_values($configType->fields));

        $this->configRepo->updateSettings($configTypeId, $newSettings);

        return redirect()->back();
    }
}

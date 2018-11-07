<?php

namespace App\Http\Controllers;

use App\Interfaces\AutomationRepositoryInterface;
use App\Interfaces\SegmentRepositoryInterface;
use Illuminate\Http\Request;

class AutomationsController extends Controller
{
    /**
     * @var SegmentRepositoryInterface
     */
    private $segmentRepository;

    /**
     * @var AutomationRepositoryInterface
     */
    private $automationRepository;

    /**
     * AutomationsController constructor.
     *
     * @param SegmentRepositoryInterface $segmentRepository
     * @param AutomationRepositoryInterface $automationRepository
     */
    public function __construct(SegmentRepositoryInterface $segmentRepository, AutomationRepositoryInterface $automationRepository)
    {
        $this->segmentRepository = $segmentRepository;
        $this->automationRepository = $automationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $automations = $this->automationRepository->paginate();
        return view('automations.index', compact('automations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $segments = $this->segmentRepository->pluck();

        return view('automations.create', compact('segments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'segment_id' => 'required'
        ]);
        $this->automationRepository->store($request->all());

        return response('Success', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $automation = $this->automationRepository->find($id);
        return view('automations.show', compact('automation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

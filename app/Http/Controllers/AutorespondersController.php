<?php

namespace App\Http\Controllers;

use App\Interfaces\AutoresponderRepositoryInterface;
use App\Interfaces\SegmentRepositoryInterface;
use Illuminate\Http\Request;

class AutorespondersController extends Controller
{
    /**
     * @var SegmentRepositoryInterface
     */
    private $segmentRepository;

    /**
     * @var AutoresponderRepositoryInterface
     */
    private $autoresponderRepository;

    /**
     * AutorespondersController constructor.
     *
     * @param SegmentRepositoryInterface $segmentRepository
     * @param AutoresponderRepositoryInterface $autoresponderRepository
     */
    public function __construct(SegmentRepositoryInterface $segmentRepository, AutoresponderRepositoryInterface $autoresponderRepository)
    {
        $this->segmentRepository = $segmentRepository;
        $this->autoresponderRepository = $autoresponderRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $autoresponders = $this->autoresponderRepository->paginate();
        return view('autoresponders.index', compact('autoresponders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $segments = $this->segmentRepository->pluck();

        return view('autoresponders.create', compact('segments'));
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
        $autoresponder = $this->autoresponderRepository->store($request->all());

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
        $autoresponder = $this->autoresponderRepository->find($id);
        return view('autoresponders.show', compact('autoresponder'));
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

<?php

namespace App\Http\Controllers;

use App\Http\Requests\SegmentRequest;
use App\Interfaces\SegmentRepositoryInterface;
use App\Interfaces\SubscriberRepositoryInterface;
use Illuminate\Http\RedirectResponse;

class SegmentsController extends Controller
{
    /**
     * @var subscriberSegmentInterface
     */
    protected $segmentRepository;

    /**
     * SubscribersController constructor.
     *
     * @param segmentRepositoryInterface $subscriberSegmentRepository
     */
    public function __construct(
        SegmentRepositoryInterface $segmentRepository
    )
    {
        $this->segmentRepository = $segmentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $segments = $this->segmentRepository->paginate('name', ['subscribersCount', 'activeSubscribersCount']);

        return view('segments.index', compact('segments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('segments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SubscriberSegmentRequest $request
     * @return RedirectResponse
     */
    public function store(SegmentRequest $request)
    {
        $this->segmentRepository->store($request->all());

        return redirect()->route('segments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        app()->abort(404, 'Not implemented');

        return view('segments.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, SubscriberRepositoryInterface $subscriberRepository)
    {
        $segment = $this->segmentRepository->find($id, ['subscribers']);
        $subscribers = $subscriberRepository->all();

        return view('segments.edit', compact('segment', 'subscribers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SubscriberListRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(SegmentRequest $request, $id)
    {
        $this->segmentRepository->update($id, $request->all());

        return redirect()->route('segments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        app()->abort(404, 'Not implemented');
    }
}

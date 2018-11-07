<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriberRequest;
use App\Interfaces\SubscriberRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Rap2hpoutre\FastExcel\FastExcel;

class SubscribersController extends Controller
{
    /**
     * @var SubscriberInterface
     */
    protected $subscriberRepository;

    /**
     * SubscribersController constructor.
     *
     * @param segmentRepositoryInterface $subscriberSegmentRepository
     */
    public function __construct(
        SubscriberRepositoryInterface $subscriberRepository
    )
    {
        $this->subscriberRepository = $subscriberRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscribers = $this->subscriberRepository->paginate('first_name');

        return view('subscribers.index', compact('subscribers'));
    }

    /**
     * Export Subscribers
     *
     * @return Response
     */
    public function export()
    {
        $subscribers = $this->subscriberRepository->export(['id', 'email', 'first_name', 'last_name', 'created_at']);

        return (new FastExcel($subscribers))->download(sprintf('subscribers-%s.csv', date('Y_m_d-H_m_s')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('subscribers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SubscriberRequest $request
     * @return RedirectResponse
     */
    public function store(SubscriberRequest $request)
    {
        $this->subscriberRepository->store($request->all());

        return redirect()->route('subscribers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subscriber = $this->subscriberRepository->find($id);

        return view('subscribers.show', compact('subscriber'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, TagRepositoryInterface $tagRepository)
    {
        $subscriber = $this->subscriberRepository->find($id);

        return view('subscribers.edit', compact('subscriber'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SubscriberRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(SubscriberRequest $request, $id)
    {
        $this->subscriberRepository->update($id, $request->all());

        return redirect()->route('subscribers.index');
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

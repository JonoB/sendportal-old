<?php

namespace App\Http\Controllers;

use App\Events\SubscriberAddedEvent;
use App\Http\Requests\SubscriberRequest;
use App\Repositories\SegmentTenantRepository;
use App\Repositories\SubscriberTenantRepository;
use Illuminate\Http\RedirectResponse;
use Rap2hpoutre\FastExcel\FastExcel;

class SubscribersController extends Controller
{
    /**
     * @var SubscriberTenantRepository
     */
    protected $subscriberRepository;

    /**
     * SubscribersController constructor.
     *
     * SubscribersController constructor.
     * @param SubscriberTenantRepository $subscriberRepository
     */
    public function __construct(SubscriberTenantRepository $subscriberRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index()
    {
        $subscribers = $this->subscriberRepository->paginate(currentTeamId(), 'first_name', ['segments'], 50, request()->all());

        return view('subscribers.index', compact('subscribers'));
    }

    /**
     * Export Subscribers
     *
     * @return string|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export()
    {
        $subscribers = $this->subscriberRepository->all('id', ['segments']);

        if ( ! $subscribers->count())
        {
            return redirect()->route('subscribers.index')->withErrors('There are no subscribers to export');
        }

        return (new FastExcel($subscribers))->download(sprintf('subscribers-%s.csv', date('Y-m-d-H-m-s')), function ($subscriber)
        {
            return [
                'id' => $subscriber->id,
                'hash' => $subscriber->hash,
                'email' => $subscriber->email,
                'first_name' => $subscriber->first_name,
                'last_name' => $subscriber->last_name,
                'created_at' => $subscriber->created_at,
                'segments' => $subscriber->segments->implode('name', ';')
            ];
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param SegmentTenantRepository $segmentRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function create(SegmentTenantRepository $segmentRepository)
    {
        $segments = $segmentRepository->all(currentTeamId());

        return view('subscribers.create', compact('segments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SubscriberRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(SubscriberRequest $request)
    {
        $subscriber = $this->subscriberRepository->store(currentTeamId(), $request->all());

        event(new SubscriberAddedEvent($subscriber));

        return redirect()->route('subscribers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function show($id)
    {
        $subscriber = $this->subscriberRepository->find(currentTeamId(), $id);

        return view('subscribers.show', compact('subscriber'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @param SegmentTenantRepository $segmentRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function edit($id, SegmentTenantRepository $segmentRepository)
    {
        $subscriber = $this->subscriberRepository->find(currentTeamId(), $id, ['segments']);

        $data = [
            'subscriber' => $subscriber,
            'segments' => $segmentRepository->all(),
            'selectedSegments' => selectedOptions('segments', $subscriber)
        ];

        return view('subscribers.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SubscriberRequest $request
     * @param $id
     * @return RedirectResponse
     * @throws \Exception
     */
    public function update(SubscriberRequest $request, $id)
    {
        $this->subscriberRepository->update(currentTeamId(), $id, $request->all());

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

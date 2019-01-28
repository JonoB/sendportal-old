<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriberStoreRequest;
use App\Http\Requests\SubscriberUpdateRequest;
use App\Interfaces\SegmentRepositoryInterface;
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
     * SubscribersController constructor.
     * @param SubscriberRepositoryInterface $subscriberRepository
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
        $subscribers = $this->subscriberRepository->paginate('first_name', ['segments'], 50, request()->all());

        return view('subscribers.index', compact('subscribers'));
    }

    /**
     * Export Subscribers
     *
     * @return string|\Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
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
     * @param SegmentRepositoryInterface $segmentRepository
     *
     * @return \Illuminate\Http\Response
     */
    public function create(SegmentRepositoryInterface $segmentRepository)
    {
        $segments = $segmentRepository->all();

        return view('subscribers.create', compact('segments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SubscriberStoreRequest $request
     *
     * @return RedirectResponse
     */
    public function store(SubscriberStoreRequest $request)
    {
        $this->subscriberRepository->store($request->validated());

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
     * @param  int $id
     * @param TagRepositoryInterface $tagRepository
     * @param SegmentRepositoryInterface $segmentRepository
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id, TagRepositoryInterface $tagRepository, SegmentRepositoryInterface $segmentRepository)
    {
        $subscriber = $this->subscriberRepository->find($id, ['segments']);

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
     * @param SubscriberUpdateRequest $request
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function update(SubscriberUpdateRequest $request, $id)
    {
        $this->subscriberRepository->update($id, $request->all());

        return redirect()->route('subscribers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return void
     */
    public function destroy($id)
    {
        app()->abort(404, 'Not implemented');
    }
}

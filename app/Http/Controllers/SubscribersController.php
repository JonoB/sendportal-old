<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriberRequest;
use App\Interfaces\SubscriberListRepositoryInterface;
use App\Interfaces\SubscriberRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubscribersController extends Controller
{
    /**
     * @var SubscriberRepositoryInterface
     */
    protected $subscriberRepository;

    /**
     * @var SubscriberListRepositoryInterface
     */
    protected $subscriberListRepository;

    /**
     * @var TagRepositoryInterface
     */
    protected $tagRepository;

    /**
     * SubscribersController constructor.
     *
     * @param SubscriberRepositoryInterface $subscriberRepository
     */
    public function __construct(
        SubscriberRepositoryInterface $subscriberRepository,
        SubscriberListRepositoryInterface $subscriberListRepository,
        TagRepositoryInterface $tagRepository
    )
    {
        $this->subscriberRepository = $subscriberRepository;
        $this->subscriberListRepository = $subscriberListRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscribers = $this->subscriberRepository->paginate('email', ['tags']);

        return view('subscribers.index', compact('subscribers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'subscriberLists' => $this->subscriberListRepository->pluck(),
            'tags' => $this->tagRepository->all(),
            'selectedTags' => [],
        ];

        return view('subscribers.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SubscriberRequest $request
     * @return RedirectResponse
     */
    public function store(SubscriberRequest $request)
    {
        $subscriber = $this->subscriberRepository->store($request->all());
        $this->subscriberRepository->syncTags($subscriber, $request->get('tags', []));

        return redirect()->route('subscribers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('subscribers.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subscriber = $this->subscriberRepository->find($id, ['tags']);

        $data = [
            'subscriber' => $subscriber,
            'subscriberLists' => $this->subscriberListRepository->pluck(),
            'tags' => $this->tagRepository->all(),
            'selectedTags' => selectedOptions('tags', $subscriber),
        ];

        return view('subscribers.edit', $data);
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
        $subscriber = $this->subscriberRepository->update($id, $request->all());
        $this->subscriberRepository->syncTags($subscriber, $request->get('tags', []));

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
        //
    }
}

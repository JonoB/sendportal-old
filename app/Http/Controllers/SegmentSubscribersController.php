<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriberRequest;
use App\Interfaces\SubscriberListRepositoryInterface;
use App\Repositories\SubscriberTenantRepository;
use App\Interfaces\TagRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class SegmentSubscribersController extends Controller
{
    /**
     * @var SubscriberTenantRepository
     */
    protected $subscriberRepository;

    /**
     * @var SubscriberListRepositoryInterface
     */
    protected $subscriberLists;

    /**
     * @var TagRepositoryInterface
     */
    protected $tagRepository;


    /**
     * SubscribersController constructor.
     */
    public function __construct(
        SubscriberTenantRepository $subscriberRepository,
        SubscriberListRepositoryInterface $subscriberLists,
        TagRepositoryInterface $tagRepository
    )
    {
        $this->subscriberRepository = $subscriberRepository;
        $this->tagRepository = $tagRepository;
        $this->subscriberLists = $subscriberLists;
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $listId
     * @return Response
     */
    public function index($listId)
    {
        $list = $this->subscriberLists->find($listId);
        $subscribers = $this->subscriberRepository->paginateListSubscribers($listId, 'email', ['tags']);

        return view('lists.subscribers.index', compact('subscribers', 'list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $listId
     * @return Response
     */
    public function create($listId)
    {
        $list = $this->subscriberLists->find($listId);
        $data = [
            'tags' => $this->tagRepository->all(),
            'selectedTags' => [],
            'list' => $list
        ];

        return view('lists.subscribers.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SubscriberRequest $request
     * @return RedirectResponse
     */
    public function store(SubscriberRequest $request, $listId)
    {
        $subscriber = $this->subscriberRepository->store($request->all() + ['subscriber_list_id' => $listId]);
        $this->subscriberRepository->syncTags($subscriber, $request->get('tags', []));

        return redirect()->route('lists.subscribers.index', $listId);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return Response
     */
    public function show($id)
    {
        app()->abort(404, 'Not implemented');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $listId
     * @param  string  $id
     * @return Response
     */
    public function edit($listId, $id)
    {
        $subscriber = $this->subscriberRepository->find($id, ['tags']);

        $data = [
            'subscriber' => $subscriber,
            'tags' => $this->tagRepository->all(),
            'selectedTags' => selectedOptions('tags', $subscriber),
            'listId' => $listId,
        ];

        return view('lists.subscribers.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SubscriberRequest $request
     * @param string $listId
     * @param string $id
     * @return RedirectResponse
     */
    public function update(SubscriberRequest $request, $listId, $id)
    {
        $subscriber = $this->subscriberRepository->update($id, $request->all());
        $this->subscriberRepository->syncTags($subscriber, $request->get('tags', []));

        return redirect()->route('lists.subscribers.index', $listId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        app()->abort(404, 'Not implemented');
    }
}

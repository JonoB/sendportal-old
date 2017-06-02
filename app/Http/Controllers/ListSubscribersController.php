<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriberRequest;
use App\Interfaces\SubscriberListRepositoryInterface;
use App\Interfaces\SubscriberRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class ListSubscribersController extends Controller
{
    /**
     * @var SubscriberRepositoryInterface
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
     * @var array
     */
    protected $validFields = [
        'email',
        'first_name',
        'last_name',
    ];

    /**
     * SubscribersController constructor.
     */
    public function __construct(
        SubscriberRepositoryInterface $subscriberRepository,
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
        $input = $request->only($this->validFields)
            + ['meta' => $this->processMetaFields($request->get('meta_fields'))]
            + ['subscriber_list_id' => $listId];

        $subscriber = $this->subscriberRepository->store($input);
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
        $input = $request->only($this->validFields) + ['meta' => $this->processMetaFields($request->get('meta_fields'))];

        $subscriber = $this->subscriberRepository->update($id, $input);
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

    /**
     * Process the meta fields to be passed to the repository
     *
     * @param array $metaFields
     *
     * @return string
     */
    protected function processMetaFields(array $metaFields = null)
    {
        if ( ! $metaFields)
        {
            return null;
        }

        return json_encode(array_map(function($meta) {
            return [
                'name' => snake_case($meta['label']),
                'label' => $meta['label'],
                'value' => $meta['value']
            ];
        }, $metaFields));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriberListRequest;
use App\Interfaces\SubscriberListRepositoryInterface;
use Illuminate\Http\RedirectResponse;

class ListsController extends Controller
{
    /**
     * @var SubscriberListRepositoryInterface
     */
    protected $subscriberListRepository;

    /**
     * SubscribersController constructor.
     *
     * @param SubscriberListRepositoryInterface $subscriberListRepository
     */
    public function __construct(
        SubscriberListRepositoryInterface $subscriberListRepository
    )
    {
        $this->subscriberListRepository = $subscriberListRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriberLists = $this->subscriberListRepository->paginate('name');

        return view('lists.index', compact('subscriberLists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lists.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SubscriberListRequest $request
     * @return RedirectResponse
     */
    public function store(SubscriberListRequest $request)
    {
        $this->subscriberListRepository->store($request->all());

        return redirect()->route('lists.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        app()->abort(404, 'Not implemented');

        return view('lists.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subscriberList = $this->subscriberListRepository->find($id);

        return view('lists.edit', compact('subscriberList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SubscriberListRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(SubscriberListRequest $request, $id)
    {
        $this->subscriberListRepository->update($id, $request->all());

        return redirect()->route('lists.index');
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

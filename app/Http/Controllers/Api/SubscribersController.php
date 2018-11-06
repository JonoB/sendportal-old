<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubscriberStoreRequest;
use App\Http\Requests\Api\SubscriberUpdateRequest;
use App\Http\Resources\Subscriber as SubscriberResource;
use App\Interfaces\SubscriberRepositoryInterface;
use App\Services\Subscribers\ApiStoreService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class SubscribersController extends Controller
{
    /**
     * @var SubscriberRepositoryInterface
     */
    protected $subscribers;

    /**
     * @var ApiStoreService
     */
    protected $apiStoreService;

    /**
     * SubscribersController constructor.
     *
     * @param SubscriberRepositoryInterface $subscribers
     * @param ApiStoreService $apiStoreService
     */
    public function __construct(
        SubscriberRepositoryInterface $subscribers,
        ApiStoreService $apiStoreService
    )
    {
        $this->subscribers = $subscribers;
        $this->apiStoreService = $apiStoreService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $subscribers = $this->subscribers->paginate('last_name');

        return SubscriberResource::collection($subscribers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SubscriberStoreRequest $request
     *
     * @return SubscriberResource
     */
    public function store(SubscriberStoreRequest $request)
    {
        $subscriber = $this->apiStoreService->createOrUpdate($request->validated());

        return new SubscriberResource($subscriber);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     *
     * @return SubscriberResource
     */
    public function show($id)
    {
        return new SubscriberResource($this->subscribers->find((int)$id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SubscriberUpdateRequest $request
     * @param string $id
     *
     * @return SubscriberResource
     */
    public function update(SubscriberUpdateRequest $request, $id)
    {
        $subscriber = $this->subscribers->update((int)$id, $request->validated());

        return new SubscriberResource($subscriber);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->subscribers->destroy((int)$id);

        return response(null, 204);
    }
}

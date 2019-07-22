<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubscriberStoreRequest;
use App\Http\Requests\Api\SubscriberUpdateRequest;
use App\Http\Resources\Subscriber as SubscriberResource;
use App\Interfaces\SubscriberRepositoryInterface;
use App\Services\Subscribers\ApiSubscriberService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class SubscribersController extends Controller
{
    /**
     * @var SubscriberRepositoryInterface
     */
    protected $subscribers;

    /**
     * @var ApiSubscriberService
     */
    protected $apiService;

    /**
     * @param SubscriberRepositoryInterface $subscribers
     * @param ApiSubscriberService $apiService
     */
    public function __construct(
        SubscriberRepositoryInterface $subscribers,
        ApiSubscriberService $apiService
    )
    {
        $this->subscribers = $subscribers;
        $this->apiService = $apiService;
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
        $subscriber = $this->apiService->store($request->validated());

        $subscriber->load('segments');

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
        return new SubscriberResource($this->subscribers->find($id, ['segments']));
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
        $subscriber = $this->subscribers->update($id, $request->validated());

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
        $this->subscribers->destroy($id);

        return response(null, 204);
    }
}

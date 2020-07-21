<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubscriberStoreRequest;
use App\Http\Requests\Api\SubscriberUpdateRequest;
use App\Http\Resources\Subscriber as SubscriberResource;
use App\Repositories\SubscriberTenantRepository;
use App\Services\Subscribers\ApiSubscriberService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class SubscribersController extends Controller
{
    /**
     * @var SubscriberTenantRepository
     */
    protected $subscribers;

    /**
     * @var ApiSubscriberService
     */
    protected $apiService;

    /**
     * SubscribersController constructor
     *
     * @param SubscriberTenantRepository $subscribers
     * @param ApiSubscriberService $apiService
     */
    public function __construct(
        SubscriberTenantRepository $subscribers,
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
     * @throws \Exception
     */
    public function index()
    {
        $subscribers = $this->subscribers->paginate(currentTeamId(), 'last_name');

        return SubscriberResource::collection($subscribers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SubscriberStoreRequest $request
     * @return SubscriberResource
     * @throws \Exception
     */
    public function store(SubscriberStoreRequest $request)
    {
        $subscriber = $this->apiService->store(currentTeamId(), $request->validated());

        $subscriber->load('segments');

        return new SubscriberResource($subscriber);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return SubscriberResource
     * @throws \Exception
     */
    public function show($id)
    {
        return new SubscriberResource($this->subscribers->find(currentTeamId(), $id, ['segments']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SubscriberUpdateRequest $request
     * @param int $id
     * @return SubscriberResource
     * @throws \Exception
     */
    public function update(SubscriberUpdateRequest $request, $id)
    {
        $subscriber = $this->subscribers->update(currentTeamId(), $id, $request->validated());

        return new SubscriberResource($subscriber);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->subscribers->destroy(currentTeamId(), $id);

        return response(null, 204);
    }
}

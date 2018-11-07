<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SubscriberSegmentDestroyRequest;
use App\Http\Requests\Api\SubscriberSegmentStoreRequest;
use App\Http\Requests\Api\SubscriberSegmentUpdateRequest;
use App\Http\Resources\Segment;
use App\Interfaces\SubscriberRepositoryInterface;
use App\Services\Subscribers\Segments\ApiDestroyService;
use App\Services\Subscribers\Segments\ApiSubscriberSegmentService;
use App\Services\Subscribers\Segments\ApiUpdateService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Segment as SegmentResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SubscriberSegmentsController extends Controller
{
    /**
     * @var SubscriberRepositoryInterface
     */
    protected $subscribers;

    /**
     * @var ApiSubscriberSegmentService
     */
    protected $apiService;

    /**
     * @param SubscriberRepositoryInterface $subscribers
     * @param ApiSubscriberSegmentService $apiService
     */
    public function __construct(
        SubscriberRepositoryInterface $subscribers,
        ApiSubscriberSegmentService $apiService
    )
    {
        $this->subscribers = $subscribers;
        $this->apiService = $apiService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $subscriberId
     *
     * @return AnonymousResourceCollection
     */
    public function index($subscriberId)
    {
        $subscriber = $this->subscribers->find($subscriberId, ['segments']);

        return SegmentResource::collection($subscriber->segments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SubscriberSegmentStoreRequest $request
     * @param string $subscriberId
     *
     * @return AnonymousResourceCollection
     */
    public function store(SubscriberSegmentStoreRequest $request, $subscriberId)
    {
        $input = $request->validated();

        $segments = $this->apiService->store($subscriberId, $input['segments']);

        return SegmentResource::collection($segments);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SubscriberSegmentUpdateRequest $request
     * @param string $subscriberId
     *
     * @return AnonymousResourceCollection
     */
    public function update(SubscriberSegmentUpdateRequest $request, $subscriberId)
    {
        $input = $request->validated();

        $segments = $this->apiService->update($subscriberId, $input['segments']);

        return SegmentResource::collection($segments);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SubscriberSegmentDestroyRequest $request
     * @param string $subscriberId
     *
     * @return AnonymousResourceCollection
     */
    public function destroy(SubscriberSegmentDestroyRequest $request, $subscriberId)
    {
        $input = $request->validated();

        $segments = $this->apiService->destroy($subscriberId, $input['segments']);

        return SegmentResource::collection($segments);
    }
}

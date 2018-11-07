<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SegmentSubscriberDestroyRequest;
use App\Http\Requests\Api\SegmentSubscriberStoreRequest;
use App\Http\Requests\Api\SegmentSubscriberUpdateRequest;
use App\Interfaces\SegmentRepositoryInterface;
use App\Services\Segments\ApiSegmentSubscriberService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Subscriber as SubscriberResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SegmentSubscribersController extends Controller
{
    /**
     * @var SegmentRepositoryInterface
     */
    protected $segments;

    /**
     * @var ApiSegmentSubscriberService
     */
    protected $apiService;

    /**
     * @param SegmentRepositoryInterface $segments
     * @param ApiSegmentSubscriberService $apiService
     */
    public function __construct(
        SegmentRepositoryInterface $segments,
        ApiSegmentSubscriberService $apiService
    )
    {
        $this->segments = $segments;
        $this->apiService = $apiService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $segmentId
     *
     * @return AnonymousResourceCollection
     */
    public function index($segmentId)
    {
        $segment = $this->segments->find($segmentId, ['subscribers']);

        return SubscriberResource::collection($segment->subscribers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SegmentSubscriberStoreRequest $request
     * @param string $segmentId
     *
     * @return AnonymousResourceCollection
     */
    public function store(SegmentSubscriberStoreRequest $request, $segmentId)
    {
        $input = $request->validated();

        $subscribers = $this->apiService->store($segmentId, $input['subscribers']);

        return SubscriberResource::collection($subscribers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SegmentSubscriberUpdateRequest $request
     * @param string $segmentId
     *
     * @return AnonymousResourceCollection
     */
    public function update(SegmentSubscriberUpdateRequest $request, $segmentId)
    {
        $input = $request->validated();

        $subscribers = $this->apiService->update($segmentId, $input['subscribers']);

        return SubscriberResource::collection($subscribers);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SegmentSubscriberDestroyRequest $request
     * @param string $segmentId
     *
     * @return AnonymousResourceCollection
     */
    public function destroy(SegmentSubscriberDestroyRequest $request, $segmentId)
    {
        $input = $request->validated();

        $subscribers = $this->apiService->destroy($segmentId, $input['subscribers']);

        return SubscriberResource::collection($subscribers);
    }
}

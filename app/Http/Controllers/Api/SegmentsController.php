<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SegmentStoreRequest;
use App\Http\Requests\Api\SegmentUpdateRequest;
use App\Http\Resources\Segment;
use App\Http\Resources\Segment as SegmentResource;
use App\Interfaces\SegmentRepositoryInterface;
use App\Services\Segments\ApiSegmentService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class SegmentsController extends Controller
{
    /**
     * @var SegmentRepositoryInterface
     */
    protected $segments;

    /**
     * @var ApiSegmentService
     */
    protected $apiService;

    /**
     * SegmentsController constructor.
     *
     * @param SegmentRepositoryInterface $segments
     * @param ApiSegmentService $apiService
     */
    public function __construct(
        SegmentRepositoryInterface $segments,
        ApiSegmentService $apiService
    )
    {
        $this->segments = $segments;
        $this->apiService = $apiService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return SegmentResource::collection($this->segments->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SegmentStoreRequest $request
     *
     * @return SegmentResource
     */
    public function store(SegmentStoreRequest $request)
    {
        $input = $request->validated();

        $segment = $this->apiService->store($input);

        $segment->load('subscribers');

        return new SegmentResource($segment);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     *
     * @return SegmentResource
     */
    public function show($id)
    {
        return new SegmentResource($this->segments->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SegmentUpdateRequest $request
     * @param string $id
     *
     * @return SegmentResource
     */
    public function update(SegmentUpdateRequest $request, $id)
    {
        $input = $request->validated();

        return new SegmentResource($this->segments->update($id, $input));
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
        $this->segments->destroy($id);

        return response(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use Storage;

use App\Services\Subscribers\ImportSubscriberService;
use App\Http\Requests\SubscribersImportRequest;
use App\Interfaces\SegmentRepositoryInterface;
use Rap2hpoutre\FastExcel\FastExcel;

class SubscribersImportController extends Controller
{
    /**
     * @var ApiSubscriberService
     */
    protected $subscriberService;

    /**
     * ImportSubscriberService $subscriberService
     *
     * @param ImportSubscriberService $subscriberService
     */
    public function __construct(ImportSubscriberService $subscriberService)
    {
        $this->subscriberService = $subscriberService;
    }

    /**
     * Show the form for upload file
     *
     * @return View
     */
    public function show(SegmentRepositoryInterface $segmentRepo)
    {
        $segments = $segmentRepo->pluck('name', 'id');

        return view('subscribers.import', compact('segments'));
    }

    /**
     * Store the Subscribers from the uploaded file
     *
     * @param  SubscribersImportRequest $request
     * @return Redirect
     */
    public function store(SubscribersImportRequest $request)
    {
        if ($request->file('file')->isValid())
        {
            $filename = str_random(16) . '.csv';

            $path = $request->file('file')->storeAs('imports', $filename);

            $subscribers = (new FastExcel)->import(storage_path('app/'. $path), function ($line) use ($request)
            {
                // TODO: validate each row beforehand
                try {
                    $data = array_only($line, ['id', 'email', 'first_name', 'last_name']);

                    $data['segments'] = $request->get('segments') ?? [];

                    $this->subscriberService->import($data);
                } catch (\Exception $e) {
                    \Log::warn($e->getMessage());
                }
            });

            Storage::disk('local')->delete('imports/' . $filename);

            return redirect()->route('subscribers.index')
                ->with('success', sprintf('Imported %d subscribers', $subscribers->count()));
        }

        return redirect()->route('subscribers.index')
            ->with('errors', $this->addToErrorsBag('file', 'The uploaded file is not valid'));
    }
}
